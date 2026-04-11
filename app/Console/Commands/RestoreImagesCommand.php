<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class RestoreImagesCommand extends Command
{
    protected $signature = 'restore:images';
    protected $description = 'Restore product images by matching timestamps';

    public function handle()
    {
        $products = Product::all();
        $files = File::files(public_path('storage/products'));
        
        $mainImages = [];
        $subImages = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            
            if (strpos($nameWithoutExt, '_') !== false) {
                // Sub image (e.g., 1772274224_0.jpg)
                list($timestamp, $index) = explode('_', $nameWithoutExt);
                $subImages[$timestamp][$index] = 'products/' . $filename;
            } else {
                // Main image (e.g., 1772274224.jpg)
                $timestamp = $nameWithoutExt;
                $mainImages[$timestamp] = 'products/' . $filename;
            }
        }

        // For each product, find the closest timestamp in $mainImages
        $matchedProducts = 0;
        foreach ($products as $product) {
            $updatedAtTs = $product->updated_at->timestamp;
            $createdAtTs = $product->created_at->timestamp;
            
            $bestMatchTs = null;
            $minDiff = 120; // Allow 2 minutes difference max
            
            foreach ($mainImages as $ts => $path) {
                // Check diff with updated_at or created_at
                $diff1 = abs((int)$ts - $updatedAtTs);
                $diff2 = abs((int)$ts - $createdAtTs);
                $diff = min($diff1, $diff2);
                
                if ($diff <= $minDiff) {
                    $minDiff = $diff;
                    $bestMatchTs = $ts;
                }
            }
            
            if ($bestMatchTs) {
                $product->image = $mainImages[$bestMatchTs];
                if (isset($subImages[$bestMatchTs])) {
                    // Sort by index so it's ordered correctly
                    ksort($subImages[$bestMatchTs]);
                    $product->images = array_values($subImages[$bestMatchTs]);
                } else {
                    $product->images = null; 
                }
                
                // Keep the color names matching images
                // e.g. if 2 sub-images + 1 main image = 3 total images => keep colors in sync
                // the user said: "khôi phục lại ảnh cũ và đặt đúng màu sắc" 
                
                $product->save();
                $this->info("Restored images for product ID {$product->id}: {$product->name}");
                $matchedProducts++;
            }
        }
        
        $this->info("Successfully matched and restored images for $matchedProducts products!");
    }
}
