<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Product;

class DumpProductsCommand extends Command
{
    protected $signature = 'dump:products';
    public function handle()
    {
        foreach (Product::all() as $p) {
            $this->info("ID: {$p->id} | Name: {$p->name} | Created: {$p->created_at->timestamp} | Updated: {$p->updated_at->timestamp}");
        }
    }
}
