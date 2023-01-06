/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
//import { Tooltip, Toast, Popover } from 'bootstrap';
import 'bootstrap';
import 'webpack-icons-installer';
import $ from 'jquery';
import 'font-awesome/css/font-awesome.min.css';

$( document ).ready(function() {

    $('#CheckArren').click(function (){
        if(this.checked){
            $('#SelectArren').removeAttr("disabled");
            //arrendatarios_loader();
        }else{
            $('#SelectArren').attr("disabled", true);
        }
    });

    $('#CheckAmbi').click(function (){
        if(this.checked){
            $('#SelectAmbi').removeAttr("disabled");
        }else{
            $('#SelectAmbi').attr("disabled", true);
        }
    });

    $('#CheckMes').click(function (){
        if(this.checked){
            $('#SelectMes').removeAttr("disabled");
        }else{
            $('#SelectMes').attr("disabled", true);
        }
    });

    $('#CheckAnio').click(function (){
        if(this.checked){
            $('#SelectAnio').removeAttr("disabled");
        }else{
            $('#SelectAnio').attr("disabled", true);
        }
    });

    $('#CheckPeriodo').click(function (){
        if(this.checked){
            $('#SelectMes_i').removeAttr("disabled");
            $('#SelectAnio_i').removeAttr("disabled");
            $('#SelectMes_f').removeAttr("disabled");
            $('#SelectAnio_f').removeAttr("disabled");
        }else{
            $('#SelectMes_i').attr("disabled", true);
            $('#SelectAnio_i').attr("disabled", true);
            $('#SelectMes_f').attr("disabled", true);
            $('#SelectAnio_f').attr("disabled", true);
        }
    });    

    $('select').change(function(){
        update_result();
    }); 

    function update_result(){
        let search=$('#search').text();
        if (search=='1'){
            $.ajax({
                type: "POST",
                url: "/searchByArrenAmbAjax",
                data: {
                    'arrendatario_id': $('#SelectArren').val(),
                    'ambiente_id': $('#SelectAmbi').val(),
                    'arrendatario_chk': $("#CheckArren").is(':checked'),
                    'ambiente_chk': $("#CheckAmbi").is(':checked'),    
                },
                async: true,
            }).done(function (html){
                $('#tableResult').html(html);
            });
        }else if(search=='2'){
            $.ajax({
                type: "POST",
                url: "/searchByArrenAmbDepoAjax",
                data: {
                    'arrendatario_id': $('#SelectArren').val(),
                    'ambiente_id': $('#SelectAmbi').val(),
                    'mes': $('#SelectMes').val(),
                    'anio': $('#SelectAnio').val(),
                    'mes_i': $('#SelectMes_i').val(),
                    'anio_i': $('#SelectAnio_i').val(),
                    'mes_f': $('#SelectMes_f').val(),
                    'anio_f': $('#SelectAnio_f').val(),
                    'arrendatario_chk': $("#CheckArren").is(':checked'),
                    'ambiente_chk': $("#CheckAmbi").is(':checked'),    
                    'mes_chk': $("#CheckMes").is(':checked'),    
                    'anio_chk': $("#CheckAnio").is(':checked'),    
                    'periodo_chk': $("#CheckPeriodo").is(':checked'),    
                },
                async: true,
            }).done(function (html){
                $('#tableResult').html(html);
            });
        }else if(search=='3'){
            $.ajax({
                type: "POST",
                url: "/searchByAmbDepoAjax",
                data: {
                    'ambiente_id': $('#SelectAmbi').val(),
                    'mes': $('#SelectMes').val(),
                    'anio': $('#SelectAnio').val(),
                    'ambiente_chk': $("#CheckAmbi").is(':checked'),    
                    'mes_chk': $("#CheckMes").is(':checked'),    
                    'anio_chk': $("#CheckAnio").is(':checked'),    
                },
                async: true,
            }).done(function (html){
                $('#tableResult').html(html);
            });
        }
    }
   
});