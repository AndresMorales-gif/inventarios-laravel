<!DOCTYPE html>
<html>
    <head>
        <title>
        </title>
    </head>
    <body>
        <style>
            body{
                font-family: Arial;
            }
            thead{
                border: 1px solid #999;
                border-radius: 2px;
                border-spacing: 0;  
            }
            th{
                font-size: 14pt;
            }
            td{
                font-size: 10pt;
                border-bottom: 1px solid #CDCDCD;

            }
            th, td{
                border-spacing: 0;
                text-align: left;
                padding-left:  2px;
                padding-right: 2px;
                padding-top: 10px;
            }
            #encabezado{
                margin-left: 30px;
                width: 100%;
                overflow: hidden;
                height: 150px;              
            }
            #logo{
                width: 25%;
                float: left;
            }
            #title{
                padding-left: 40px;
                padding-top: 40px;
                width: 50%;
                float: left;
            }
            #fecha{
                padding-top: 100px;
                width: 25%;
                float: left;
            }
        </style>
        <div id="encabezado">
            <div id="title">
                <h1>
                    Factura N°{{$numberInvoice}}
                </h1>
            </div>
            <div id="fecha">
                <h3>
                    Hoy: {{date('d/m/Y')}}
                </h3>
            </div>
        </div>
        <div id="tabla">
            <table class="table table-striped table-condensed table-hover table-responsive">
                <thead>
                    <tr>
                        <th width="15%">
                            Producto
                        </th>
                        <th width="15%">
                            Description
                        </th>
                        <th width="15%">
                            Número de lote
                        </th>
                        <th width="15%">
                            Fecha de vencimiento
                        </th>
                        <th width="12%">
                            Cantidad
                        </th>
                        <th width="12%">
                            Precio unidad
                        </th>
                        <th width="15%">
                            Precio total
                        </th>                        
                    </tr>
                </thead>
                <tbody id="cuerpoTabla">
                    @foreach($saleDetails as $saleDetail)
                    <tr>
                        <td>
                            {{$saleDetail['name']}}
                        </td>
                        <td>
                            {{$saleDetail['description']}}
                        </td>
                        <td>
                            {{$saleDetail['lot_number']}}
                        </td>
                        <td>
                            {{$saleDetail['expiration_date']}}
                        </td>
                        <td>
                            {{$saleDetail['quantity']}}
                        </td>
                        <td>
                            $ {{$saleDetail['priceU']}}
                        </td>
                        <td>
                            $ {{$saleDetail['priceT']}}
                        </td>
                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>