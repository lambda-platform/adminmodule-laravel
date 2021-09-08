<?php

namespace Lambda\Lambda\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lambda\Agent\Models\Menu;
use Lambda\Agent\Models\MenuItem;
use TCG\Voyager\Facades\Voyager;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::all();
        if($menus) {
            return response()->json(['status' => true, 'menus' => $menus]);
        }
        return response()->json(['status' => false, 'message' => 'Том Цэсийн жагсаалтад алдаа гарлаа']);
    }
    public function builder($id)
    {
        $menu = Menu::findOrFail($id);

//        $this->authorize('edit', $menu);

//        $isModelTranslatable = is_bread_translatable(Voyager::model('MenuItem'));
        if($menu) {
            return response()->json(['status' => true, 'menu' => $menu]);
        }
        return response()->json(['status' => false, 'message' => 'Том Цэсэнд алдаа гарлаа']);
//        return Voyager::view('voyager::menus.builder', compact('menu', 'isModelTranslatable'));
    }

    public function delete_menu($id)
    {
        $item = MenuItem::findOrFail($id);

//        $this->authorize('delete', $item->menu);

//        $item->deleteAttributeTranslation('title');

        if($item->destroy($id)) {
            return response()->json(['status' => true, 'message' => 'Цэс амжилттай устлаа']);
        } else {
            return response()->json(['status' => false, 'message' => 'Цэс устгахад алдаа гарлаа']);
        }
    }

    public function add_item(Request $request)
    {
//        $menu = Menu::all();
//
//        $this->authorize('add', $menu);

        $data = $this->prepareParameters(
            $request->all()
        );

        unset($data['id']);
        $data['order'] = MenuItem::highestOrderMenuItem();

        // Check if is translatable
//        $_isTranslatable = is_bread_translatable(Voyager::model('MenuItem'));
//        if ($_isTranslatable) {
//            // Prepare data before saving the menu
//            $trans = $this->prepareMenuTranslations($data);
//        }

        $menuItem = MenuItem::create($data);

        // Save menu translations
//        if ($_isTranslatable) {
//            $menuItem->setAttributeTranslations('title', $trans, true);
//        }

        if($menuItem) {
            return response()->json(['status' => true, 'menuItem' => $menuItem, 'message' => 'Цэс амжилттай нэмэгдлээ']);
        } else {
            return response()->json(['status' => false, 'message' => 'Цэс нэмэхэд алдаа гарлаа']);
        }
//        return redirect()
//            ->route('voyager.menus.builder', [$data['menu_id']])
//            ->with([
//                'message'    => __('voyager::menu_builder.successfully_created'),
//                'alert-type' => 'success',
//            ]);
    }

//    public function update_item(Request $request)
//    {
//        $id = $request->input('id');
//        $data = $this->prepareParameters(
//            $request->except(['id'])
//        );
//
//        $menuItem = Voyager::model('MenuItem')->findOrFail($id);
//
//        $this->authorize('edit', $menuItem->menu);
//
//        if (is_bread_translatable($menuItem)) {
//            $trans = $this->prepareMenuTranslations($data);
//
//            // Save menu translations
//            $menuItem->setAttributeTranslations('title', $trans, true);
//        }
//
//        $menuItem->update($data);
//
//        return redirect()
//            ->route('voyager.menus.builder', [$menuItem->menu_id])
//            ->with([
//                'message'    => __('voyager::menu_builder.successfully_updated'),
//                'alert-type' => 'success',
//            ]);
//    }
//
//    public function order_item(Request $request)
//    {
//        $menuItemOrder = json_decode($request->input('order'));
//
//        $this->orderMenu($menuItemOrder, null);
//    }
//
//    private function orderMenu(array $menuItems, $parentId)
//    {
//        foreach ($menuItems as $index => $menuItem) {
//            $item = Voyager::model('MenuItem')->findOrFail($menuItem->id);
//            $item->order = $index + 1;
//            $item->parent_id = $parentId;
//            $item->save();
//
//            if (isset($menuItem->children)) {
//                $this->orderMenu($menuItem->children, $item->id);
//            }
//        }
//    }

    protected function prepareParameters($parameters)
    {
        switch (array_get($parameters, 'type')) {
            case 'route':
                $parameters['url'] = null;
                break;
            default:
                $parameters['route'] = null;
                $parameters['parameters'] = '';
                break;
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }

    /**
     * Prepare menu translations.
     *
     * @param array $data menu data
     *
     * @return JSON translated item
     */
//    protected function prepareMenuTranslations(&$data)
//    {
//        $trans = json_decode($data['title_i18n'], true);
//
//        // Set field value with the default locale
//        $data['title'] = $trans[config('voyager.multilingual.default', 'en')];
//
//        unset($data['title_i18n']);     // Remove hidden input holding translations
//        unset($data['i18n_selector']);  // Remove language selector input radio
//
//        return $trans;
//    }
}
