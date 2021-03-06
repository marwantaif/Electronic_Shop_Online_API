<?php


namespace App\Repositories\Product;


use App\Category;
use App\Product;
use App\Repositories\EloquentRepository;
use App\User;
use DB;

class ProductEloquentRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
       return \App\Product::class;
    }

    public function getProducts()
    {
//        $query = "select products.id, products.name, products.photo, products.description, SUM(import_products.amount) AS amount, categories.name as category, producers.name as producer from products left join categories on products.category_id = categories.id left join producers on producers.id = products.producer_id left join import_products on import_products.product_id = products.id group by products.id limit 15 offset 0";
//        return DB::select($query);
        $nows = date(now()->toDateString());
        return DB::table('products as p')
                  ->leftJoin('categories','p.category_id','=','categories.id')
                  ->leftJoin('producers','producers.id','=','p.producer_id')
                  ->leftJoin('import_products','import_products.product_id','=','p.id')
                  ->leftJoin('promotion_products','p.id','=','promotion_products.product_id')
                  ->leftJoin('promotions','promotion_products.promotion_id','=','promotions.id')
                  ->select('p.id','p.name','p.photo','p.description',
                  DB::raw('SUM(import_products.amount) AS amount') ,'categories.name as category',
                  DB::raw('MAX(import_products.export_price) AS price'),
                  DB::raw('MAX(promotion_products.title) AS discount'),
                  'producers.name as producer')
                  ->where('promotion_products.title',null)
                  ->Orwhere('promotions.start_date','<=',$nows)
                  ->where('promotions.end_date','>=',$nows)
                  ->groupBy('p.id')
                  ->get();
    }

    public function getProductByCategory($id)
    {

//        return Category::with('categories.products')
//            ->where('categories.id',$id)
//            ->orWhere('categories.parrent_id',$id)
//            ->get();
        $nows = date(now()->toDateString());
        return DB::table('products as p')
            ->leftJoin('categories','p.category_id','=','categories.id')
            ->leftJoin('producers','producers.id','=','p.producer_id')
            ->leftJoin('import_products','import_products.product_id','=','p.id')

            ->select('p.id','p.name','p.photo','p.description',
                DB::raw('SUM(import_products.amount) AS amount') ,'categories.name as category',
                DB::raw('MAX(import_products.export_price) AS price'))
            ->where('categories.id',$id)
            ->orWhere('categories.parrent_id',$id)
            ->groupBy('p.id')
            ->get();
//         $singleCategory = Category::find($id);
//         return $singleCategory->products;

    }

    public function showProductById($id){
        $nows = date(now()->toDateString());
        $product = Product::find($id);
        $detail_promotion = $product->promotions()->get()->pluck('name','id')->toArray();
        $discount = $product->promotions()->get()->pluck('pivot.title','pivot.product_id')->toArray();
        $promotion = [$detail_promotion,
        $discount
        ];
        $review = [DB::table('products as p')
            ->join('reviews','p.id','=','reviews.product_id')
            ->join('users','reviews.user_id','=','users.id')
            ->select('users.name','reviews.content','reviews.rating')
            ->where('p.id',$id)
            ->get()];

        $product = DB::table('products')
              ->leftJoin('categories','products.category_id','=','categories.id')
              ->leftJoin('producers','producers.id','=','products.producer_id')
              ->leftJoin('import_products','import_products.product_id','=','products.id')
              ->select('products.id','products.name','products.photo','products.description','products.information',
                DB::raw('SUM(import_products.amount) AS amount') ,'categories.name as category',
                DB::raw('MAX(import_products.export_price) AS price'),'producers.name as producer')
              ->where('products.id',$id)
             ->groupBy('products.id')
             ->get();
         return  $product->concat($promotion)->concat($review);
    }

    public function getSaleProduct(){
        $nows = date(now()->toDateString());
        return DB::table('products as p')
            ->leftJoin('categories','p.category_id','=','categories.id')
            ->leftJoin('producers','producers.id','=','p.producer_id')
            ->leftJoin('import_products','import_products.product_id','=','p.id')
            ->leftJoin('promotion_products','p.id','=','promotion_products.product_id')
            ->leftJoin('promotions','promotion_products.promotion_id','=','promotions.id')
            ->select('p.id','p.name','p.photo','p.description',
                DB::raw('SUM(import_products.amount) AS amount') ,'categories.name as category',
                DB::raw('MAX(import_products.export_price) AS price'),
                DB::raw('MAX(promotion_products.title) AS discount'),
                'producers.name as producer')
            ->Orwhere('promotions.start_date','<=',$nows)
            ->where('promotions.end_date','>=',$nows)
            ->groupBy('p.id')
            ->get();
    }


    public function getNewProduct()
    {
        $nows = date(now()->toDateString());
        return DB::table('products as p')
            ->where('p.status_id',3)
            ->leftJoin('categories','p.category_id','=','categories.id')
            ->leftJoin('producers','producers.id','=','p.producer_id')
            ->leftJoin('import_products','import_products.product_id','=','p.id')
            ->select('p.id','p.name','p.photo','p.description',
                DB::raw('SUM(import_products.amount) AS amount') ,'categories.name as category',
                DB::raw('MAX(import_products.export_price) AS price'),
                'producers.name as producer')
            ->groupBy('p.id')
            ->get();

    }

    public function getBestSellProduct()
    {
        // TODO: Implement getBestSellProduct() method.
    }
}
