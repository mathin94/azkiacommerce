<?php

namespace App\Jobs;

use App\Models\Shop\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateCategoryStatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private ?int $category_id = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->category_id) {
            $categories = Category::has('products')->get();

            $categories?->each(function ($category) {
                $this->recalculate($category);
            });

            return;
        }

        $category = Category::find($this->category_id);
        $this->recalculate($category);
    }

    private function recalculate(Category $category)
    {
        $category->product_count = $category->products()->visible()->published()->count();
        $category->save();
    }
}
