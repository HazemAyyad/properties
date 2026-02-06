<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>{{__('Invoice No :'). $package->order_no}}</title>


</head>
<style>

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    .tableD,.tableD td,.tableD th {
        border: 1px solid;
        padding: 10px;
    }
    *{
        font-family: 'Poppins', sans-serif;
    }

    .tableD {
        width: 100%;
        border-collapse: collapse;
    }
    table{
        width: 100%;
    }
    .title{
        font-weight: 800;
        font-size: 25px;
    }
    hr {
        display: block;
        unicode-bidi: isolate;
        margin-block-start: 0.5em;
        margin-block-end: 0.5em;
        margin-inline-start: auto;
        margin-inline-end: auto;
        overflow: hidden;
        border-style: inset;
        border-width: 0.1px;
        color: #df3a27;
    }
</style>

<body style="direction: ltr !important;">


<div >
    <table >
        <tr>
            <td style="width: 50%;">
                <img style="margin-right: auto; height: 100px; object-fit: contain;"   src="{{env('SITE_URL').'/public/assets/images/logo.png'}}" alt="">
            </td>
            <td style="width: 50%;">
                <div style="float: right ;">
                    <table>
                        <tr>
                            <td style="text-align: right">{{__('Date')}}: {{$package->shipping_date}}</td>

                        </tr>
                        <tr>
                            <td class="title">{{__('Shipment:')}} ES{{$package->order_no}}</td>

                        </tr>
                        <tr>
                            <td class="title" >{{__('Order Invoice:')}} {{$package->invoice->invoice_no}}</td>
                        </tr>
                    </table>

                </div>
            </td>
        </tr>
    </table>
    <hr>

    <table >
        <tr>
            <td style="width: 50%;">
                <div>
                    <h3 class="title">{{__('Shipping From')}} </h3>
                    <span>
                            {{$package->address_from->full_name}}
                            <br>
                            {{$package->address_from->address_1}}
                            <br>
                            {{$package->address_from->city.','.$package->address_from->state_province_region.','.$package->address_from->postal_code}}
                            <br>
                            {{$package->address_from->full_country?$package->address_from->full_country->Country:$package->address_from->country}}
                            <br>
                            {{$package->address_from->full_phone}}
                            <br>
                            {{$package->address_from->email}}
                        </span>
                </div>
            </td>
            <td style="width: 50%;">
                <div>
                    <h3 class="title">Delivering To</h3>
                    <span>
                            {{$package->address_going->full_name}}
                            <br>
                            {{$package->address_going->address_1}}
                            <br>
                            {{$package->address_going->city.','.$package->address_going->state_province_region.','.$package->address_going->postal_code}}
                            <br>
                            {{$package->address_going->full_country?$package->address_going->full_country->Country:$package->address_going->country}}
                            <br>
                            {{$package->address_going->full_phone}}
                            <br>
                            {{$package->address_going->email}}
                        </span>
                </div>
            </td>
        </tr>
    </table>

    <hr>

    <table >
        <tr >
            <td style="width: 50%;">
                <div>
                    <h3 class="title">{{__('Package Size')}} </h3>
                    @if($package->type_v=='0')
                        <span   >{{$package->weight}} {{__('lbs')}} ({{$package->length}} in x {{$package->width}} in x {{$package->height}} in),</span>
                        @if(count($package->multi_package)>0)
                            @foreach($package->multi_package as $item)
                                <span  >{{$item['weight']}} {{__('lbs')}} ({{$item['length']}} in x {{$item['width']}} in x {{$item['height']}} in),</span>

                            @endforeach
                        @endif
                    @elseif($package->type_v=='1')
                        @if(count($package->packages)>0)
                            @foreach($package->packages as $item)
                                <span >{{$item['weight']}} {{$item['unit_weight']=='1'?'lbs':'KG'}} ({{$item['length']}}  x {{$item['width']}}  x {{$item['height']}} ) {{$item['unit_dimension']=='1'?'in':'cm'}}</span>,

                            @endforeach
                        @endif
                    @endif
                </div>
            </td>
            <td>
                <div>
                    <h3 class="title">{{__('Service Details')}}</h3>
                    <span>
                           {{$package->shipping_method}}:
                          {{$package->tracking_number}}
                        </span>
                </div>
            </td>
        </tr>
    </table>

    <hr>

    <div class="">
        <h3 class="title">{{__('Box Contents')}}</h3>
        <table style="width: 100%;">
            <tr>
                <td>{{__('Item Description')}} </td>
                <td>{{__('Made In')}}</td>
                <td>{{__('QTY')}} </td>
                <td> {{__('Item Value')}} </td>
                <td> {{__('Total Value')}} </td>
            </tr>
            @foreach($package->items as $item)
            <tr style="font-weight: 600;">
                <td>({{$loop->index+1}}) {{$item->condition==1?__('New'):__('Used')}}: {{$item->description}}</td>
                <td>{{$item->full_country->Country}}</td>
                <td>{{$item->quantity}} </td>
                <td> ${{$item->value}} </td>
                <td> ${{$item->total}} </td>
            </tr>
            @endforeach
        </table>
    </div>

    <hr>

    <h3 style="font-weight: 600; text-align: right;">{{__('Total Box Value')}}: ${{$package->total_cost_items}}</h3>

    <hr>
    <table>

        <tr>

            <td style="width: 50%;">
                <div >
                    <div >
                        <p style="font-size: 13px">{{__('By providing your electronic signature, you confirm as true and correct that you are the shipper and that you are not signing on behalf of another person or entity. By sending your package, you agree that there will be no hazardous or prohibited items included. The refund policy is not applicable once an order has been shipped.')}}</p>
                        <h3 style="font-weight: 600; margin-bottom: 4px;">{{__('Signature')}}</h3>
                        <table>

                            <tr>
                                <td> {{$invoice->signature}}</td>

                            </tr>

                            <tr>

                                <td ><img style=" height: 100px; object-fit: contain;"   src="{{env('SITE_URL').'/public/'.$invoice->signature_online}}" alt=""></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </td>
            <td style=" vertical-align:top">
                <div >
                    <div >
                        <h3 style="font-weight: 600; margin-bottom: 4px;">{{__('Order Summary')}}</h3>
                        @if($package->type_invoice==1)
                            <table>
                                <tr>
                                    <td>{{__('Shipping Cost via')}} <br/> {{$package->shipping_method}}</td>
                                    <td style="text-align: right;">${{$package->shipping_cost}}</td>
                                </tr>
                                @if($package->cost_protection>0)
                                    <tr>
                                        <td>{{__('EarthShip Protect')}} </td>
                                        <td style="text-align: right;">${{$package->cost_protection}}</td>
                                    </tr>
                                @endif
                                @if($package->discount_golden>0)
                                    <tr>
                                        <td style="color:#c79335;">{{__('Golden discount')}} </td>
                                        <td style="color:#c79335;text-align: right;">-${{$package->shipping_cost*($package->discount_golden/100)}}</td>
                                    </tr>
                                @endif
                                @if($package->discount_coins>0)
                                    <tr>
                                        <td style="color:#ff8f5e;">{{__('Discount')}} {{$package->use_coins}} {{__('ES Coins')}}</td>
                                        <td style="color:#ff8f5e;text-align: right;">-${{$package->discount_coins}}</td>
                                    </tr>
                                @endif
                                @if($package->promo_code_id!=null)
                                    <tr>
                                        <td style="color:#DF3A27;">{{__('Discount')}} </td>
                                        <td style="color:#DF3A27;text-align: right;">-${{$package->promo_code_value}}</td>
                                    </tr>
                                @endif
                            </table>
                            <hr style="margin-bottom: 0px;">
                            <table style="font-weight: 600;margin-bottom: 2px;">
                                <tr>
                                    <td>{{__('Total')}}:</td>
                                    <td style="text-align: right;">
                                        ${{$package->cost_protection+$package->shipping_cost-($package->promo_code_value)-($package->discount_coins)-(($package->shipping_cost*($package->discount_golden/100)))}}
                                    </td>
                                </tr>
                            </table>
                            <hr style="margin-bottom: 0px;">
                            <table style="color: #df3a27; font-weight: 600;margin-bottom: 0;">
                                <tr>
                                    <td>{{__('Paid via')}} </td>
                                    <td style="text-align: right;">
                                        @if($package->type_pay=='zelle')
                                            {{$package->type_pay}}
                                        @elseif($package->type_pay=='venmo')
                                            {{$package->type_pay}}
                                        @elseif($package->type_pay=='paypal')
                                            {{$package->type_pay}}
                                        @elseif($package->type_pay=='paylater')
                                            {{$package->type_pay}}
                                        @elseif($package->type_pay=='new card')
                                            {{$package->payments_response['card_details']['card']['card_brand']}}
                                        @elseif($package->type_pay=='Saved card')
                                            {{$package->payments_response['card_details']['card']['card_brand']}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif
                        @if($package->type_invoice==2)
                            <table>
                                <tr>
                                    <td>{{$package->invoice->reason}}</td>
                                    <td style="text-align: right;">${{$package->invoice->amount}}</td>
                                </tr>

                            </table>
                            <hr style="margin-bottom: 0px;">
                            <table style="font-weight: 600;margin-bottom: 2px;">
                                <tr>
                                    <td>{{__('Total')}}:</td>
                                    <td style="text-align: right;">
                                        ${{$package->invoice->amount}}
                                    </td>
                                </tr>
                            </table>
                            <hr style="margin-bottom: 0px;">
                            <table style="color: #df3a27; font-weight: 600;margin-bottom: 0;">
                                <tr>
                                    <td>{{__('Paid via')}} </td>
                                    <td style="text-align: right;">
                                        @if($package->invoice->type_pay=='zelle')
                                            {{$package->invoice->type_pay}}
                                        @elseif($package->invoice->type_pay=='venmo')
                                            {{$package->invoice->type_pay}}
                                        @elseif($package->invoice->type_pay=='paypal')
                                            {{$package->invoice->type_pay}}
                                        @elseif($package->invoice->type_pay=='paylater')
                                            {{$package->invoice->type_pay}}
                                        @elseif($package->invoice->type_pay=='new card')
                                            {{$package->payments_response['card_details']['card']['card_brand']}}
                                        @elseif($package->invoice->type_pay=='Saved card')
                                            {{$package->payments_response['card_details']['card']['card_brand']}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif


                    </div>
                </div>
            </td>
        </tr>
    </table>






</div>



</body>

</html>
