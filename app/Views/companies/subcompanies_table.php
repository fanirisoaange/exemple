<tr>
    <td>
        <?php if ($company['parent_id'] == 0): ?>
            <form method="post" class="float-left">
                <input type="hidden" name="company_main_id" value="<?= $company['main_id']; ?>"/>
                <input type="hidden" name="company_parent_id" value="<?= $company['id']; ?>"/>
                <button type="submit" class="btn btn-outline-primary btn-xs"><i class="fas fa-project-diagram"></i></button>
            </form>
        <?php endif; ?>
        <?php if (!empty($company['children'])): ?>
            <button class="btn btn-outline-primary btn-xs ml-1 display_subcompany"> <i class="fas fa-th-list"></i> </button>
        <?php endif; ?>
        <a href="<?= route_to('company_detail', $company['id']); ?>" class="btn btn-outline-primary btn-xs ml-1"><i class="fas fa-eye"></i></a>
        <a href="<?= route_to('company_edit', $company['id']); ?>" class="btn btn-outline-warning btn-xs"><i class="fas fa-edit"></i></a>
    </td>
    <td><?= $company['fiscal_name']; ?></td>
    <td><?= $company['city_display']; ?></td>
    <td><?= $company['status_name']; ?></td>
</tr>