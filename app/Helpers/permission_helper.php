<?php
use App\Enum\UserGroupType;

function permissionLib()
{
    return new App\Libraries\Permissions;
}

function isLoggedIn()
{
    return permissionLib()->isLoggedIn();
}

function isInGroup($groups)
{
    return permissionLib()->isInGroup($groups);
}

function isAdmin()
{
    return permissionLib()->isInGroup(UserGroupType::ADMIN);
}

function isCardata()
{
    return permissionLib()->isInGroup([UserGroupType::CARDATA_ADMIN, UserGroupType::CARDATA_MEMBER]);
}

function isHeadAdmin()
{
    return (userHasCompanyAccess())
        && (isAdmin() || isCardata()
            || permissionLib()->isInGroup(UserGroupType::HEAD_ADMIN));
}

function isHeadAccounting()
{
    return (userHasCompanyAccess())
        && (isHeadAdmin()
            || permissionLib()->isInGroup(UserGroupType::HEAD_ACCOUNTING));
}

function isHeadMember()
{
    return (userHasCompanyAccess())
        && (isHeadAdmin()
            || permissionLib()->isInGroup(UserGroupType::HEAD_MEMBER));
}

function isZoneAdmin()
{
    return (userHasCompanyAccess())
        && (isHeadAdmin()
            || permissionLib()->isInGroup(UserGroupType::ZONE_ADMIN));
}

function isZoneAccounting()
{
    return (userHasCompanyAccess())
        && (isHeadAccounting() || isZoneAdmin()
            || permissionLib()->isInGroup(UserGroupType::ZONE_ACCOUNTING));
}

function isZoneMember()
{
    return (userHasCompanyAccess())
        && (isHeadMember() || isZoneAdmin()
            || permissionLib()->isInGroup(UserGroupType::ZONE_MEMBER));
}

function isMemberAdmin()
{
    return (userHasCompanyAccess())
        && (isZoneAdmin()
            || permissionLib()->isInGroup(UserGroupType::MEMBER_ADMIN));
}

function isMemberAccounting()
{
    return (userHasCompanyAccess())
        && (isZoneAccounting() || isMemberAdmin()
            || permissionLib()->isInGroup(UserGroupType::MEMBER_ACCOUTING));
}

function isMember()
{
    return (userHasCompanyAccess())
        && (isZoneMember() || isMemberAdmin()
            || permissionLib()->isInGroup(UserGroupType::MEMBER_MEMBER));
}

function hasGroupPermission($permission_var)
{
    return permissionLib()->hasGroupPermission($permission_var);
}

function userHasCompanyAccess()
{
    if (isAdmin() || isCardata()) {
        return true;
    }

    return permissionLib()->isInCompany();
}

function isCustomer()
{
    return ! isAdmin() && ! isCardata();
}

function accessDenied()
{
    return redirect()->to(base_url().'/dashboard');
}

function userHasEditUserAccess(int $userId)
{
    return isMemberAdmin() || (!isMemberAdmin() && $_SESSION['user_id'] == $userId);
}
