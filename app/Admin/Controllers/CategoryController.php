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
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HasResourceActions;

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
        if ($request->get('active') == 'on'){
            $active = '1';
        }else{
            $active = '0';
        }

        $category = new Category([
            'parent_id' => $request->get('parent_id'),
            'title'=> $request->get('title'),
            'order'=> $request->get('order'),
            'slug'=> $slug,
            'description'=> $request->get('description'),
            'img'=> $request->get('img'),
            'active'=> $active,
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
        $grid->active('Active')->display(function ($active) {
            return $active ? trans('admin.yes') : trans('admin.no');
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->filter(function($filter){
            $filter->like('title', 'title');
            $filter->like('slug', 'slug');
            $filter->date('created_at', 'По дате создания');
            $filter->equal('active')->radio([
                ''   => 'Все',
                0    => 'неактивные',
                1    => 'активные',
            ]);
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
        $show->active('Active');
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
        $grid = Admin::form(Category::class, function (Form $form) {
            $states = [
                'on' => ['value' => 1, 'text' => 'активно', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'неактивно', 'color' => 'danger'],
            ];
            $form->display('id', 'ID');
            $form->text('title', 'title');
            $form->select('parent_id', trans('admin.parent_id_category'))->options(Category::selectOptions());
            $form->text('description', 'Description');
            $form->number('order', 'Order');
            $form->text('slug', 'Символьный код');
            $form->image('img', 'img')->move('public/upload/categories/');
            $form->switch('active', '')->states($states);
        });

        return $grid;
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

dd($request->file('img'));
        if ($request->get('active') == 'on'){
            $active = '1';
        }else{
            $active = '0';
        }

        $category->parent_id = $request->get('parent_id');
        $category->title = $request->get('title');
        $category->order = $request->get('order');
        $category->slug = $request->get('slug');
        $category->description = $request->get('description');
        $category->img = $request->get('img');
        $category->active = $active;
        $category->save();

        return redirect('/admin/category');
//        return redirect('/admin/category/'.$id.'/edit');
    }
}
