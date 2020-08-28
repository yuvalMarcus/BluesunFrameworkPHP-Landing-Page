<?php

class Permission {

    public static function getPermissions(array &$data, int $group, string $name = 'desdata') {

        $data['data_group_roles_relationship_by_groupid'] = [];
        
        $data['mysql']['executearray'] = [$group];
        $data['mysql']['table_name'] = 'group_roles_relationship';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE groupid = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'data_group_roles_relationship_by_groupid');

        $data[$name] = [];

        foreach ($data['data_group_roles_relationship_by_groupid'] as $val) {

            $data['mysql']['executearray'] = [$val['grouproleid']];
            $data['mysql']['table_name'] = 'group_roles';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE id = ?';
            $data['mysql']['clear'] = TRUE;

            Database::getDatasAddInLoop($data, $name);
        }
        
        unset($data['data_group_roles_relationship_by_groupid']);
    }

}
