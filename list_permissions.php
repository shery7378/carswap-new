<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

foreach (Permission::all() as $p) {
    echo $p->name . " ({$p->guard_name})\n";
}
