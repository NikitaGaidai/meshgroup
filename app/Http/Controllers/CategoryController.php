<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $categories = Category::with('children')->get();

        $formatted = [];
        foreach ($categories as $category) {
            array_push($formatted, $category);

            if (count($category->children) > 0) {
                $category->children = self::getChildren($category);
            }
        }

        return response()->json([
           'categories' => $formatted,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;

        if($request->parent != 'no_parent')
            $category->parent_id = $request->parent;

        if($category->save())
            return response()->json(['category' => $category]);
        else
            throw new \Exception('Failed when saving...', 100);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if($request->parent_id != null) {
            $parent = Category::findOrFail($request->parent_id);
            $category->makeChildOf($parent);
        }

        $category->name = $request->name;

        if($category->save())
            return response()->json(['category' => $category]);
        else
            throw new \Exception('Failed when saving...', 100);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $category = Category::with('products')->where('id', $id)->first();

        $category->products()->delete();

        $children = self::getChildren($category);

        foreach ($children as $child) {
            $child->products()->delete();
        }

        $category->children()->delete();

        if($category->delete())
            return response()->json(true);
        else
            return response()->json(false, 500);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsFrom($id)
    {
        $category = Category::with('products')->where('id', $id)->first();

        $category->parents = $category->getAncestors();
        $category->children = $category->getDescendants();

        $subproducts = array();
        foreach ($category->children as $child)
        {
            foreach ($child->products as $product)
            {
                array_push($subproducts, $product);
            }
        }

        $category->subproducts = $subproducts;

        return response()->json([
            'category' => $category,
        ]);
    }

    /* HELPER METHODS */

    /**
     * @param Category $category
     * @return array
     */
    private function getChildren(Category $category)
    {
        $children = [];
        foreach ($category->children as $child) {
            array_push($children, $child);

            if(count($child->children) > 0) {
                self::getChildren($child);
            }
            else {
                $child->children = [];
            }
        }

        return $children;
    }

}
