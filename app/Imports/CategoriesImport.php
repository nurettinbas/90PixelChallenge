<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Category;
use Mail;
use Log;

class CategoriesImport implements ToCollection, WithHeadingRow
{
  public function collection(Collection $rows)
    {
        $categories = [];
        foreach ($rows as $row){
          for ($i=1; $i <= 6 ; $i++) {
            if (!isset($row['kategori_'.$i]) || !$row['kategori_'.$i]) continue;
            $categoryName = 'category'.$i;
            $parentCategoryName = 'category'.($i-1);
            if (isset($categories[$row['kategori_'.$i]])) {
              $$categoryName = $categories[$row['kategori_'.$i]];
            }else {
              $$categoryName = new Category();
              $$categoryName->name = $row['kategori_'.$i];
              $$categoryName->parent_category_id = $i == 1 ? 0 : $$parentCategoryName->id;
              $$categoryName->save();
              $categories[$row['kategori_'.$i]] = $$categoryName;
            }
          }
        }
        Mail::send('mail',[
                'content'=>"Kategoriler Yüklendi!"
            ],function($m){
                  $m
                  ->to('buradayim@90pixel.com')
                  ->subject('Kategoriler Yüklendi!');
              });
    }
}
