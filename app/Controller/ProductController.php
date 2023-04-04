<?php

namespace App\Controller;

use App\Core\Application;
use App\Core\Controller;
use App\Core\DbModel;
use App\Core\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
      $className=$request->getBody()['productType'];
      unset($request->getBody()['productType']);
      $productClass = $this->createInstance()[$className];
      $instance = new $productClass();
      $instance->loadData($request->getBody());
      $primaryKey=$instance->parentPrimaryKey();
      $primaryKeyValue=$instance->parentPrimaryKeyValue();
      /** Saving data */
      if(!$instance->checkIfRecordExists(
          $instance->parentSchemaName(),
          $instance->parentTableName(),
          ["column" => $primaryKey,
          "value" => $primaryKeyValue]
      )) {
          $dbParentResponse = $instance->save(
              $instance->parentSchemaName(),
              $instance->parentTableName(),
              $instance->parentColumnNames());
          if($dbParentResponse['success']){
              $recordForeignKey=$instance->getRecordById(
              $instance->parentSchemaName(),
              $instance->parentTableName(),
              $dbParentResponse['productId']);
              $foreignKeyValue=$recordForeignKey[$instance->foreignKey()];
              $instance->setProductId(intval($foreignKeyValue));

              $dbChildResponse = $instance->save(
                  $instance->schemaName(),
                  $instance->tableName(),
                  $instance->columnNames()
              );
              return json_encode($dbChildResponse);
          }
          return json_encode($dbParentResponse);
      } else {
          return json_encode(["success" => false, "message" => "This code {$primaryKeyValue} already exists"]);
      }
    }
    public function getProducts(Request $request)
    {
        $classes=$this->createInstance();
        $allProducts=[];
        foreach ($classes as $class)
        {
            $instance = new $class();
            $products = $instance->getAllByType($instance->getType());

            foreach($products as $product){
                $value=$product[$instance->foreignKey()];
                $baseProduct=$instance->getRecordByColumn(
                    $instance->parentSchemaName(),
                    $instance->parentTableName(),
                    ["column" => $instance->foreignKey(), "value" => $value]);
                foreach ($instance->columnNames() as $key => $value){
                    $product[$key]=$product[$value];
                    if($key!=$value)
                        unset($product[$value]);
                }
                if(is_array($baseProduct))
                {
                    $product=array_merge($baseProduct, $product);
                    $instance->loadData($product);
                    $product=$instance->setRealKeys((array)$instance, array_keys($product));
                    $allProducts[] = $product;
                }
            }
        }
        return json_encode($allProducts);
    }
    public function deleteproduct(Request $request){
        $productsToBeDeleted=[];
        for($i=2;$i<count($request->getParams()); $i++){
            array_push($productsToBeDeleted, $request->getParams()[$i]);
        }
        /** Delete by SKUCODE IN PARENT AND CHILE TABLE */
        $index=0;
        foreach($productsToBeDeleted as $deleteProduct)
        {
            $dbParentResponse = DbModel::deleteByColumn(Application::$app->database->getDbName(), "product", [
                "column" => "skuCode",
                "value" => $deleteProduct
            ]);
            $dbChildResponse = DbModel::deleteByColumn(Application::$app->database->getDbName(), "productType", [
                "column" => "skuCode",
                "value" => $deleteProduct
            ]);
            $index++;

            $responses[] = $dbParentResponse && $dbChildResponse;
        }

        if(!in_array(false, $responses)) {
            return json_encode(["success" => true, "message" => "Deleted ID's successully"]);
        }
        return json_encode(["success" => false, "Some ID's were not deleted"]);
    }
    public function createInstance()
    {
        return  [
            'Furniture' => \App\Model\Furniture::class,
            'Book' => \App\Model\Book::class,
            'DvdDisk' => \App\Model\DvdDisk::class,
        ];
    }
}