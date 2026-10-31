<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo \App\Models\IncomingGood::count() . " transactions\n";
echo \App\Models\IncomingGoodItem::count() . " items\n";
foreach (\App\Models\IncomingGood::all() as $t) {
    echo $t->code . ' | ' . $t->supplier . ' | ' . $t->total_kg . ' kg | Rp ' . $t->total_price . "\n";
    foreach ($t->items as $i) {
        echo "    Item: " . $i->name . ' | ' . $i->qty . ' kg | Rp ' . $i->price . "\n";
    }
}
echo "\nCoffee stocks:\n";
foreach (\App\Models\Coffee::all() as $c) {
    echo $c->name . ' => ' . $c->stock . ' ' . $c->unit . "\n";
}
