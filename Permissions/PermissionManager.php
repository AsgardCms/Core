<?php namespace Modules\Core\Permissions;

class PermissionManager
{
    /**
     * @var Module
     */
    private $module;

    /**
     */
    public function __construct()
    {
        $this->module = app('modules');
    }

    /**
     * Get the permissions from all the enabled modules
     * @return array
     */
    public function all()
    {
        $permissions = [];
        foreach ($this->module->enabled() as $enabledModule) {
            $configuration = config(strtolower('asgard.' . $enabledModule->getName()) . '.permissions');
            if ($configuration) {
                $permissions[$enabledModule->getName()] = $configuration;
            }
        }

        return $permissions;
    }

    /**
     * Return a correctly type casted permissions array
     * @param $permissions
     * @return array
     */
    public function clean($permissions)
    {
        if (!$permissions) {
            return [];
        }
        $cleanedPermissions = [];
        foreach ($permissions as $permissionName => $checkedPermission) {
            $cleanedPermissions[$permissionName] = $this->getState($checkedPermission);
        }

        return $cleanedPermissions;
    }

    /**
     * @param $checkedPermission
     * @return bool
     */
    protected function getState($checkedPermission)
    {
        if ($checkedPermission == 'true') {
            return true;
        }

        if ($checkedPermission == 'false') {
            return false;
        }

        return (bool) $checkedPermission;
    }
}
