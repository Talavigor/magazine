<?php
/**
 * Created by PhpStorm.
 * User: korak
 * Date: 14.04.2019
 * Time: 23:09
 */

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class ActiveCategory
{
    protected $id;
    protected $path;

    public function __construct($id)
    {
        $this->id = $id;
        $this->path = '/admin/category';
    }

    protected function script()
    {
        return <<<SCRIPT

$('.hide-category').on('click', function () {
    console.log($(this).data('id'));
    
                var id = $(this).data('id');
            swal({
                title: "Скрыть категорию",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Да",
                showLoaderOnConfirm: true,
                cancelButtonText: "Нет",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '{$this->path}/' + id,
                            data: {
                                _method:'put',
                                _token:LA.token,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');
                                toastr.success('success');
                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(result) {
                var data = result.value;
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            });

});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success fa fa-check hide-category' data-id='{$this->id}'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }

}