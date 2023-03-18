<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Media;
use App\Models\Messages;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\Seller;
use App\Models\SellerCategory;
use App\Models\User;
use Database\Factories\CommentFactory;
use Database\Factories\MessagesFactory;
use Database\Factories\OrderFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Media::factory(5)->create();
        User::factory(5)->create();
        Seller::factory(5)->create();
        Post::factory(3)->create();
        Like::factory(3)->create();
        Comment::factory(3)->create();
        Follower::factory(3)->create();
        Messages::factory(3)->create();
        Order::factory(3)->create();
        Product::factory(3)->create();
        Review::factory(3)->create();
        OrderProduct::factory(3)->create();
        Category::factory(3)->create();
        SellerCategory::factory(3)->create();
        ProductCategory::factory(3)->create();
    }
}
