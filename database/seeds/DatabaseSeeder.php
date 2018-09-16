<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->command->getOutput()->writeln("<fg=black;bg=green> Initializing seeders ... </>");

        //TRUNCATE TABLES
        User::truncate();
        Product::truncate();
        Category::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();
        $this->command->getOutput()->writeln("<fg=black;bg=green> Emptied tables ... </>");

        $this->command->getOutput()->writeln("<fg=black;bg=green> Inserting data ... </>");
        $this->command->getOutput()->progressStart(5);

        //VARIABLES OF QUANTITIES
        $userQuantity = 100;
        $categoryQuantity = 10;
        $productQuantity = 100;
        $transactionQuantity = 100;
        $this->command->getOutput()->progressAdvance();

        //INSERT USERS
        factory(User::class, $userQuantity)->create();
        $this->command->getOutput()->progressAdvance();

        //INSERT CATEGORIES
        $categories = factory(Category::class, $categoryQuantity)->create();
        $this->command->getOutput()->progressAdvance();

        //INSERT PRODUCTS
        factory(Product::class, $productQuantity)->create()->each(
            function($product) use(&$categories){
                $randomCategories = $categories->random(mt_rand(1, 5))->pluck('id');

                $product->categories()->attach($randomCategories);
            }
        );
        $this->command->getOutput()->progressAdvance();

        //INSERT TRANSACTIONS
        factory(Transaction::class, $transactionQuantity)->create();
        $this->command->getOutput()->progressAdvance();

        Schema::enableForeignKeyConstraints();

        $this->command->getOutput()->progressFinish();
        $this->command->getOutput()->writeln("<fg=black;bg=green> Done!. </>");

    }
}
