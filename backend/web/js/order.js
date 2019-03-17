$(function () {



    //置顶 
      //排序
    $('.content-sort').click(function (e) {
        e.preventDefault();
        var dataUrl = $(this).attr('data-url');
        if (dataUrl) {
            krajeeDialog.prompt({label: '置顶序列', placeholder: '输入序列'}, function (sort) {
                if (sort) {
                    krajeeDialog.confirm('确认序列：' + sort + '?', function (result) {
                        if (result) {
                            $.get(dataUrl, {sort: sort}, function (data) {
                                krajeeDialog.alert(data.msg ? data.msg : '出错，稍后重试！');
                                // window.location.reload();
                            });
                        }
                    });
                }
            });
        }
    });

    
});