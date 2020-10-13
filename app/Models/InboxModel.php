<?php

namespace App\Models;

use CodeIgniter\Model;

class InboxModel extends Model {
	const PAGELENGTH = 15;
	const CONTENTPREVIEW = 45;

	private $userId;

	public function __construct()
	{
		parent::__construct();

		$this->ionAuth = new \IonAuth\Libraries\IonAuth();
		$this->builder = $this->db->table("messages");
	}

	public function getMessages(int $page): array
	{
		$id = $this->getUserId();
		$messages = $this->builder->orderBy("send_at DESC")->getWhere(
			['recipient_id' => $id, 'trashed' => 0, 'type' => 'recipient'],
			self::PAGELENGTH,
			(($page - 1) * 15)
		)->getResultArray();

		foreach ($messages as $k => $v)
		{
			$messages[$k]['content'] = trim(
				strip_tags($messages[$k]["content"])
			);
			if (strlen($messages[$k]['content']) > self::CONTENTPREVIEW)
			{
				$messages[$k]['content'] = substr(
						$messages[$k]['content'],
						0,
						self::CONTENTPREVIEW
					)."...";
			}
			$r = $this->db->table('users')->select(["first_name", "last_name"])
					 ->where("id", $messages[$k]['sender_id'])->get()
					 ->getResult()[0];
			$messages[$k]['send_at'] = $this->getRelativeTime(
				$messages[$k]["send_at"]
			);
			$messages[$k]['sender'] = $r->first_name." ".$r->last_name;
		}

		return $messages;
	}

	public function getDraftMessages(int $page): array
	{
		$id = $this->getUserId();
		$messages = $this->builder->orderBy("send_at DESC")->getWhere(
			['sender_id' => $id, 'trashed' => 0, 'type' => 'draft'],
			self::PAGELENGTH,
			(($page - 1) * self::PAGELENGTH)
		)->getResultArray();

		foreach ($messages as $k => $v)
		{
			$messages[$k]['content'] = trim(
				strip_tags($messages[$k]["content"])
			);
			$messages[$k]['send_at'] = $this->getRelativeTime(
				$messages[$k]["send_at"]
			);
			if (strlen($messages[$k]['content']) > self::CONTENTPREVIEW)
			{
				$messages[$k]['content'] = substr(
						$messages[$k]['content'],
						0,
						self::CONTENTPREVIEW
					)."...";
			}
			$recs = explode(
				";",
				$messages[$k]['recipient_id']
			); // Recipients array

			$r = $this->db->table('users')->select(["first_name", "last_name"])
					 ->where("id", $recs[0])->get()->getResult()[0];
			$messages[$k]['recipient'] = $r->first_name." ".$r->last_name;

			if (count($recs) > 1)
			{
				$messages[$k]['recipient'] .= " and ".(count($recs) - 1)
					." others";
			}
		}

		return $messages;
	}

	public function getTrashedMessages(int $page): array
	{
		$id = $this->getUserId();
		$messages = $this->db->query(
			"SELECT * FROM messages WHERE ((recipient_id = ".$id
			." AND type = 'recipient') OR ((type = 'sent' OR type='draft') AND sender_id = "
			.$id.")) AND trashed = 1 ORDER BY send_at DESC LIMIT "
			.(self::PAGELENGTH + 1)." OFFSET ".($page - 1) * self::PAGELENGTH
		)->getResultArray();

		foreach ($messages as $k => $v)
		{
			$messages[$k]['content'] = trim(
				strip_tags($messages[$k]["content"])
			);
			if (strlen($messages[$k]['content']) > self::CONTENTPREVIEW)
			{
				$messages[$k]['content'] = substr(
						$messages[$k]['content'],
						0,
						self::CONTENTPREVIEW
					)."...";
			}
			if ($messages[$k]["type"] == "sent"
				|| $messages[$k]["type"] == "draft"
			)
			{
				$recs = explode(
					";",
					$messages[$k]['recipient_id']
				); // Recipients array

				$r = $this->db->table('users')->select(
					["first_name", "last_name"]
				)->where("id", $recs[0])->get()->getResult()[0];
				$messages[$k]['recipient'] = $r->first_name." ".$r->last_name;

				if (count($recs) > 1)
				{
					$messages[$k]['recipient'] .= " and ".(count($recs) - 1)
						." others";
				}
			} else
			{
				$r = $this->db->table('users')->select(
					["first_name", "last_name"]
				)->where("id", $messages[$k]['sender_id'])->get()->getResult(
				)[0];
				$messages[$k]['sender'] = $r->first_name." ".$r->last_name;
			}
			$messages[$k]['send_at'] = $this->getRelativeTime(
				$messages[$k]["send_at"]
			);
		}

		return $messages;
	}

	public function searchMessages(string $q, int $page, string $t): array
	{
		$id = $this->getUserId();
		$res = ["nextPage" => 0];

		$messages = $this->db->query(
			"SELECT messages.*, users.first_name, users.last_name FROM messages INNER JOIN users ON users.id = messages.sender_id WHERE (messages.subject LIKE '%"
			.$q."%' OR users.first_name LIKE '%".$q
			."%' OR users.last_name LIKE '%".$q
			."%') AND type = 'recipient' AND trashed = 0 AND messages.recipient_id = "
			.$id." ORDER BY send_at DESC LIMIT ".(self::PAGELENGTH + 1)
			." OFFSET ".($page - 1) * self::PAGELENGTH
		)->getResultArray();

		if (isset($messages[self::PAGELENGTH + 1]))
		{
			$res['nextPage'] = 1;
			unset($messages[self::PAGELENGTH + 1]);
		}
		foreach ($messages as $k => $v)
		{

			$messages[$k]['content'] = trim(
				strip_tags($messages[$k]["content"])
			);
			if (strlen($messages[$k]['content']) > self::CONTENTPREVIEW)
			{
				$messages[$k]['content'] = substr(
						$messages[$k]['content'],
						0,
						self::CONTENTPREVIEW
					)."...";
			}
			$messages[$k]['send_at'] = $this->getRelativeTime(
				$messages[$k]["send_at"]
			);
			if ($t == "inbox")
			{
				$messages[$k]['sender'] = $messages[$k]['first_name']." "
					.$messages[$k]['last_name'];
			}
		}
		$res["messages"] = $messages;
		$res['count'] = count($messages);

		return $res;
	}


	public function getTotalMessageCount(): int
	{
		$id = $this->getUserId();

		return $this->db->table('messages')->where(
			['recipient_id' => $id, "trashed" => 0, 'type' => 'recipient']
		)->countAllResults();
	}

	public function getTotalDraftMessageCount(): int
	{
		$id = $this->getUserId();

		return $this->db->table('messages')->where(
			['sender_id' => $id, "trashed" => 0, 'type' => 'draft']
		)->countAllResults();
	}

	public function getTotalTrashedMessageCount(): int
	{
		$id = $this->getUserId();

		return $this->db->table('messages')->where(
			['recipient_id' => $id, "trashed" => 1, 'type' => 'recipient']
		)->countAllResults();
	}

	public function getSentMessagesCount(): int
	{
		$id = $this->getUserId();

		return $this->db->table('messages')->where(
			['sender_id' => $id, "trashed" => 0, 'type' => 'sent']
		)->countAllResults();
	}

	public function getUnreadMessagesCount(): int
	{
		$id = $this->getUserId();

		return $this->db->table('messages')->where(
			[
				'recipient_id' => $id,
				"type"         => 'recipient',
				'seen'         => 0,
				"trashed"      => 0,
			]
		)->countAllResults();
	}

	public function getLastMessage()
	{
		$id = $this->getUserId();

		$last = $this->db->table('messages')->select("send_at")->where(
				[
					'recipient_id' => $id,
					"type"         => 'recipient',
					'seen'         => 0,
					"trashed"      => 0,
				]
			)->orderBy('id',"desc")->limit(1)->get()->getResultArray();
		if(is_null($last) || empty($last))
			return null;
		else
			return $last[0]['send_at'];
	}
	
		
	public function getContacts(): array
	{
		$id = $this->getUserId();

		return $this->db->table('users')->select(
			["id", "first_name", "last_name"]
		)->get()->getResultArray();
	}

	public function deleteOne(int $id)
	{
		$this->db->table("messages")->delete(["id" => $id]);
	}

	public function putTrash(int $id)
	{
		$this->db->table("messages")->set(["trashed" => 1, "seen" => 1])->where(
			["id" => $id]
		)->update();
	}

	public function mailSeen(int $id)
	{
		$this->db->table('messages')->set(['seen' => 1])->where(["id" => $id])
			->update();
	}

	public function getSentMessages(int $page): array
	{
		$id = $this->getUserId();


		$messages = $this->builder->orderBy("send_at DESC")->getWhere(
			['sender_id' => $id, 'type' => 'sent', 'trashed' => 0],
			self::PAGELENGTH,
			(($page - 1) * self::PAGELENGTH)
		)->getResultArray();

		foreach ($messages as $k => $v)
		{
			$messages[$k]['content'] = trim(
				strip_tags($messages[$k]["content"])
			);
			if (strlen($messages[$k]['content']) > self::CONTENTPREVIEW)
			{
				$messages[$k]['content'] = substr(
						$messages[$k]['content'],
						0,
						self::CONTENTPREVIEW
					)."...";
			}

			$recs = explode(";", $messages[$k]['recipient_id']);

			$r = $this->db->table('users')->select(["first_name", "last_name"])
					 ->where("id", $recs[0])->get()->getResult()[0];
			$messages[$k]['recipient'] = $r->first_name." ".$r->last_name;
			if (count($recs) > 1)
			{
				$messages[$k]['recipient'] .= " and ".(count($recs) - 1)
					." others";
			}
			$messages[$k]['send_at'] = $this->getRelativeTime(
				$messages[$k]["send_at"]
			);
		}

		return $messages;
	}


	public function getMail(int $id): object
	{

		$message = $this->builder->where('id', $id)->get()->getResult()[0];
		if ($message->type == 'sent')
		{
			$message->recipients = "";
			foreach (explode(";", $message->recipient_id) as $r)
			{
				if ($r)
				{
					$sender = $this->db->table('users')->select(
						["first_name", "last_name"]
					)->where("id", $r)->get()->getResult()[0];
					$message->recipients .= $sender->first_name." "
						.$sender->last_name." - ";
				}
			}
			$message->recipients = substr($message->recipients, 0, -2);
		}
		$sender = $this->db->table('users')->select(["first_name", "last_name"])
					  ->where("id", $message->sender_id)->get()->getResult()[0];
		$message->sender = $sender->first_name." ".$sender->last_name;


		return $message;
	}

	public function setAttachments(int $id, string $a)
	{
		$this->builder->set(["attachments" => $a])->where('id', $id)->update();
	}

	public function sendDraft(int $id, string $to, string $sub, string $content)
	{
		$this->builder->set(
			[
				"recipient_id" => $to,
				"subject"      => $sub,
				"content"      => $content,
				"type"         => "sent",
				"send_at"      => date("Y-m-d H:i:s"),
			]
		)->where('id', $id)->update();
	}

	public function setFavorite(int $id, int $fav) {
		$this->builder->set(["favorite" => $fav])->where("id", $id)->update();
	}
	public function human_filesize(int $bytes, int $decimals = 2): string
	{
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor))
			.@$sz[$factor];
	}

	public function getRelativeTime(string $date): string
	{
		$time = time() - strtotime($date);

		if ($time > 0)
		{
			$when = trad("ago");
		} elseif ($time < 0)
		{
			$when = trad("in about");
		} else
		{
			return trad("Less than a second");
		}
		$time = abs($time);


		$times = [
			31104000 => 'year{s}',
			2592000  => 'month{s}',
			86400    => 'day{s}',
			3600     => 'hour{s}',
			60       => 'minute{s}',
			1        => 'seconde{s}',
		];

		foreach ($times as $seconds => $unit)
		{
			$delta = round($time / $seconds);

			if ($delta >= 1)
			{
				if ($delta == 1)
				{
					$unit = str_replace('{s}', '', $unit);
				} else
				{
					$unit = str_replace('{s}', 's', $unit);
				}

				if ($unit == 'day')
				{
					return trad("Yesterday");
				} else
				{
					return $delta." ".$unit." ".$when;
				}
			}
		}
	}

	public function getUserId()
	{
		if ($this->userId)
		{
			return $this->userId;
		}

		$row = $this->ionAuth->user()->row();
		if ( ! $row)
		{
			return;
		}
		$this->userId = $row->id;

		return $this->userId;
	}
}
