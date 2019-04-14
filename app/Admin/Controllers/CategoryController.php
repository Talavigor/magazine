<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Tree;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Routing\Controller as RouteController;
use Encore\Admin\Controllers\ModelForm;

class CategoryController extends Controller
{
    use HasResourceActions;
//    use ModelForm;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    public function treeCategory(Content $content)
    {
        return Admin::content(function (Content $content) {
            $content->header('Categories');
            $content->body(Category::tree(function ($tree) {
                $tree->branch(function ($branch) {
                    return "<strong>{$branch['title']}</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID: {$branch['id']}&nbsp;&nbsp;Uri: {$branch['slug']}";
                });
            }));
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $categoryModel = new Category();

        return $categoryModel::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['title']}</strong>";

                return $payload;
            });
        });
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->body($this->form());
    }

    public function store(Request $request){
        $slug = Str::slug($request->get('title'), '-');

        $category = new Category([
            'parent_id' => $request->get('parent_id'),
            'title'=> $request->get('title'),
            'order'=> $request->get('order'),
            'slug'=> $slug,
            'description'=> $request->get('description'),
            'img'=> $request->get('img'),
        ]);
        $category->save();

        return redirect('/admin/category');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->id('Id');
        $grid->parent_id('Parent id');
        $grid->title('Title');
        $grid->order('Order');
        $grid->slug('Slug');
        $grid->description('Description');
        $grid->img('Img');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->filter(function($filter){
            $filter->like('title', 'title');
            $filter->like('slug', 'slug');
            $filter->date('created_at', 'По дате создания');


        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Category::findOrFail($id));

        $show->id('Id');
        $show->parent_id('Parent id');
        $show->title('Title');
        $show->order('Order');
        $show->slug('Slug');
        $show->description('Description');
        $show->img('Img');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $categoryModel = new Category();
        $form = new Form($categoryModel);
        $form->select('parent_id', trans('admin.parent_id'))->options($categoryModel::selectOptions());
//        $form->number('parent_id', 'ID Родителя');
        $form->text('title', 'Название');
        $form->number('order', 'Сортировка');
        $form->text('slug', 'Символьный код');
        $form->text('description', 'Описание');
        $form->image('img', 'Фото');

        return $form;
    }
}
