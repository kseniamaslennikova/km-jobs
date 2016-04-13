/**
 * Scripts for KM Jobs plugin
 */

(function($) {

    function jobs_filter_output(city,branch){
         // проверяем, есть ли в базе вакансии и выводим их список
         var data = {
             action: "ajax_jobs_listing",
             city: city,
             branch: branch
         };

         $.ajax({
             url: ajaxurl,
             type: 'get',
             dataType: 'html',
             data: data,
             cache: false,
             success: function(data) {
                 console.log('зашел в ветку success');
                 $(".kmjob_list_container").html(data);

             },
             error: function (data) {
                 console.log('зашел в ветку error');
             }
         });
     }

    $(document).ready(function(){
        console.log('Подключили файл скриптов');
        // проверяем, есть ли в базе вакансии и выводим их список        
        //запускаем фильтрацию вакансий по филиалу
        jobs_filter_output('','');

        console.log('viewport width= ' + viewportWidth());
        console.log('window width= ' + $(window).width());
        console.log('device width= ' + window.screen.width);


    });

    $("#kmjobs_citylocation").change(function () {
        console.log('Сработало событие смены города');
        var selectedText = $(this).find("option:selected").text();
        var selectedValue = $(this).val();
        console.log("Selected Text: " + selectedText + " Value: " + selectedValue);
        if (selectedValue!='Все города') {
            //наполняем список филиалами для данного города
            //если таковые имеются
            // входные данные для выборки филиалов
            var branchdata = {                
                action: "ajax_kmjobs_filter",
                city: selectedValue
            };
            $.ajax({
                url: ajaxurl,
                type: 'get',                
                dataType : "json",
                data: branchdata,
                cache: false,
                success: function (response) {
                    console.log('branchdata зашел в ветку success');                    

                    var branchbox_html=response.branchbox_html;
                    var jobslist_html=response.jobslist_html;

                    $("#kmjobs_branchlocation").html(branchbox_html);

                    var countBranches = $("#kmjobs_branchlocation").children().length;
                    console.log('количество филиалов в списке=' + countBranches);
                    if (countBranches > 1) {
                        //активируем выпадающий список филиалов, если он не пуст
                        $("#kmjobs_branchlocation").prop('disabled', false);
                    }
                    else {
                        //деактивируем выпадающий список филиалов
                        $("#kmjobs_branchlocation").prop('disabled', true);
                    }
                    //запускаем фильтрацию вакансий по городу                    
                    $(".kmjob_list_container").html(jobslist_html);

                },
                error: function (branchdata) {
                    console.log('branchdata зашел в ветку error');
                }
            });
        }
        else {
            $("#kmjobs_branchlocation").html('<option value="Все филиалы" selected>Все филиалы</option>');
            //деактивируем выпадающий список филиалов
            $("#kmjobs_branchlocation").prop('disabled', true);
            
            //запускаем фильтрацию вакансий по городу            
            // входные данные для выборки филиалов
            var branchdata = {                
                action: "ajax_kmjobs_filter",
                city: ''
            };
            $.ajax({
                url: ajaxurl,
                type: 'get',                
                dataType : "json",
                data: branchdata,
                cache: false,
                success: function (response) {
                    console.log('branchdata зашел в ветку success');                    
                    var jobslist_html=response.jobslist_html;                    
                    //запускаем фильтрацию вакансий по городу                    
                    $(".kmjob_list_container").html(jobslist_html);

                },
                error: function (branchdata) {
                    console.log('branchdata зашел в ветку error');
                }
            });
        }
    });

    $("#kmjobs_branchlocation").change(function () {
        console.log('Сработало событие смены филиала');
        var selectedText = $(this).find("option:selected").text();
        var selectedValue = $(this).val();
        console.log("Selected Text: " + selectedText + " Value: " + selectedValue);

        var citySelectedText = $("#kmjobs_citylocation").find("option:selected").text();
        var citySelectedValue = $("#kmjobs_citylocation").val();

        //запускаем фильтрацию вакансий по филиалу
        jobs_filter_output(citySelectedText,selectedText);        

    });


    function viewportWidth() {
        if (typeof window.innerWidth != 'undefined')  {
            return window.innerWidth;
        }
        else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth !='undefined' && document.documentElement.clientWidth != 0)  {
            return document.documentElement.clientWidth;
        }
        else  {
            return viewportwidth = document.getElementsByTagName('body')[0].clientWidth;
        }
    }

    $(window).resize(function () {
        console.log('viewport width= ' + viewportWidth());
        console.log('window width= ' + $(window).width());
        console.log('device width= ' + window.screen.width);

    });


})(jQuery);
