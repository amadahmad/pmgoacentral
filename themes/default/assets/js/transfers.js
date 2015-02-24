$(document).ready(function () {

// Order level shipping and discoutn localStorage 
    $('#tostatus').change(function (e) {
        localStorage.setItem('tostatus', $(this).val());
    });
    if (tostatus = localStorage.getItem('tostatus')) {
        $('#tostatus').select2("val", tostatus);
        if(tostatus == 'completed') {
            $('#tostatus').select2("readonly", true);
        }
    }
    var old_shipping;
    $('#toshipping').focus(function () {
        old_shipping = $(this).val();
    }).change(function () {
        if (!is_numeric($(this).val())) {
            $(this).val(old_shipping);
            bootbox.alert('Unexpected value provided!');
            return;
        } else {
            shipping = $(this).val() ? parseFloat($(this).val()) : '0';
        }
        localStorage.setItem('toshipping', shipping);
        var gtotal = total + product_tax  + shipping;
        $('#gtotal').text(formatMoney(gtotal));
    });
    if (toshipping = localStorage.getItem('toshipping')) {
        shipping = parseFloat(toshipping);
        $('#toshipping').val(shipping);
    }
//localStorage.clear();
// If there is any item in localStorage
    if (localStorage.getItem('toitems')) {
        loadItems();
    }

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                if (localStorage.getItem('toitems')) {
                    localStorage.removeItem('toitems');
                }
                if (localStorage.getItem('toshipping')) {
                    localStorage.removeItem('toshipping');
                }
                if (localStorage.getItem('toref')) {
                    localStorage.removeItem('toref');
                }
                if (localStorage.getItem('to_warehouse')) {
                    localStorage.removeItem('to_warehouse');
                }
                if (localStorage.getItem('tonote')) {
                    localStorage.removeItem('tonote');
                }
                if (localStorage.getItem('from_warehouse')) {
                    localStorage.removeItem('from_warehouse');
                }
                if (localStorage.getItem('todate')) {
                    localStorage.removeItem('todate');
                }
                if (localStorage.getItem('tostatus')) {
                    localStorage.removeItem('tostatus');
                }
                /*total = 0; count = 0;product_tax = 0; invoice_tax = 0; total_discount = 0;
                 $('#posupplier').select2('readonly', false);
                 $('#pocurrency').select2('readonly', false);
                 $('#ponote').redactor('set', '');
                 $('#toTable tbody').empty();
                 $('#total').text(formatMoney(total));
                 $('#titems').text(0);
                 $('#tds').text(formatMoney(0));
                 if (site.settings.tax1) {
                 $('#ttax1').text(formatMoney(0));
                 }
                 if (site.settings.tax2 != 0) {
                 $('#ttax2').text(formatMoney(0));
                 }
                 $('#gtotal').text(formatMoney(0));
                 $(this).parent("form").trigger('reset');*/
                $('#modal-loading').show();
                location.reload();
            }
        });
    });

// save and load the fields in and/or from localStorage

    $('#toref').change(function (e) {
        localStorage.setItem('toref', $(this).val());
    });
    if (toref = localStorage.getItem('toref')) {
        $('#toref').val(toref);
    }
    $('#to_warehouse').change(function (e) {
        localStorage.setItem('to_warehouse', $(this).val());
    });
    if (to_warehouse = localStorage.getItem('to_warehouse')) {
        $('#to_warehouse').select2("val", to_warehouse);
    }
    $('#from_warehouse').change(function (e) {
        localStorage.setItem('from_warehouse', $(this).val());
    });
    if (from_warehouse = localStorage.getItem('from_warehouse')) {
        $('#from_warehouse').select2("val", from_warehouse);
        if (count > 1) {
            $('#from_warehouse').select2("readonly", true);
        }
    }

    //$(document).on('change', '#tonote', function (e) {
    $('#tonote').redactor('destroy');
    $('#tonote').redactor({
        buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
        formattingTags: ['p', 'pre', 'h3', 'h4'],
        minHeight: 100,
        changeCallback: function (e) {
            var v = this.get();
            localStorage.setItem('tonote', v);
        }
    });
    if (tonote = localStorage.getItem('tonote')) {
        $('#tonote').redactor('set', tonote);
    }
    
    $(document).on('change', '.rexpiry', function () { 
        var item_id = $(this).closest('tr').attr('data-item-id');
        toitems[item_id].row.expiry = $(this).val();
        localStorage.setItem('toitems', JSON.stringify(toitems));
    });


// prevent default action upon enter
    $('body').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });


    /* ---------------------- 
     * Delete Row Method 
     * ---------------------- */
    /*$(document).on('click', '.podel', function () {
     var row = $(this).closest('tr'), item_id = row.attr('data-item-id');
     delete poitems[item_id];
     row.remove();
     if(poitems.hasOwnProperty(item_id)) { } else {
     localStorage.setItem('poitems', JSON.stringify(poitems));
     loadItems();
     return;
     }
     });*/

    $(document).on('click', '.todel', function () {
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        if (site.settings.tax1 == 1) {
            var itax = row.children().children('.sproduct_tax').text();
            var iptax = itax.split(') ');
            var iproduct_tax = parseFloat(iptax[1]);
            product_tax -= iproduct_tax;
        }
        var iqty = parseFloat(row.children().children('.rquantity').val());
        var icost = parseFloat(row.children().children('.rcost').val());
        an -= 1;
        total -= (iqty * icost);
        count -= iqty;

        var gtotal = (total + product_tax) + shipping;
        $('#total').text(formatMoney(total));
        $('#titems').text(count - 1);
        $('#ttax1').text(formatMoney(product_tax));
        $('#gtotal').text(formatMoney(gtotal));
        if (count == 1) {
            $('#from_warehouse').select2('readonly', false);
        }
        //console.log(poitems[item_id].row.name + ' is being removed.');
        delete toitems[item_id];
        localStorage.setItem('toitems', JSON.stringify(toitems));
        row.remove();

    });

    /* --------------------------
     * Edit Row Quantity Method 
     -------------------------- */
    var old_row_qty;
    $(document).on("focus", '.rquantity', function () {
        old_row_qty = $(this).val();
    }).on("change", '.rquantity', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_row_qty);
            bootbox.alert('Unexpected value provided!');
            return;
        }
        var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
        toitems[item_id].row.qty = new_qty;
        localStorage.setItem('toitems', JSON.stringify(toitems));
        loadItems();
    });
    
    /* --------------------------
     * Edit Row Cost Method 
     -------------------------- */
    var old_cost;
    $(document).on("focus", '.rcost', function () {
        old_cost = $(this).val();
    }).on("change", '.rcost', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_cost);
            bootbox.alert('Unexpected value provided!');
            return;
        }
        var new_cost = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
        toitems[item_id].row.cost = new_cost;
        localStorage.setItem('toitems', JSON.stringify(toitems));
        loadItems();
    });
    
    $(document).on("click", '#removeReadonly', function () { 
       $('#from_warehouse').select2('readonly', false); 
       return false;
    });
    
    
});
/* -----------------------
 * Misc Actions
 ----------------------- */

function loadItems() {

    if (localStorage.getItem('toitems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        $("#toTable tbody").empty();
        toitems = JSON.parse(localStorage.getItem('toitems'));
        $.each(toitems, function () {
            var item = this;
            var item_id = item.id;
            if (site.settings.item_addition == 1)
                toitems[item_id] = item;
            else
                toitems[count] = item;
            
            var from_warehouse = localStorage.getItem('from_warehouse'), check = false;
            var item_cost = item.row.cost, item_qty = item.row.qty, item_rqty = item.row.quantity, item_bqty = item.row.quantity_balance, item_expiry = item.row.expiry, item_tax_method = item.row.tax_method, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            
            var pr_tax = item.tax_rate, row_total;
            var pr_tax_val = 0, pr_tax_rate = 0;
            if (site.settings.tax1 == 1) {
                if (pr_tax !== false) {
                    row_total = item_cost * item_qty;
                    if (pr_tax.type == 2) {
                        if (item_tax_method == '0') {
                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate;
                            row_total -= pr_tax_val;
                        } else {
                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate * item_qty;
                        }
                    }
                    if (pr_tax.type == 1) {
                        if (item_tax_method == '0') {
                            pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                            //pr_tax_val = (row_total * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = pr_tax.rate + '%';
                            item_cost -= pr_tax_val;
                        } else {
                            pr_tax_val = ((item_cost) * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = pr_tax.rate + '%';
                        }
                    }
                    product_tax += pr_tax_val * item_qty;
                }
            }
               
            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + (site.settings.item_addition == 1 ? item_id : count) + '" data-item-id="' + (site.settings.item_addition == 1 ? item_id : count) + '"></tr>');
            tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span></td>';
            tr_html += '<td><input class="form-control text-center rcost" name="net_cost[]" type="text" value="' + formatMoney(item_cost) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="cost_' + row_no + '" onClick="this.select();"></td>';
            tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value="' + item_bqty + '"><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item_qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_expiry == 1) {
                tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            }
            if (site.settings.tax1 == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }
            tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(parseFloat(item_cost) * parseFloat(item_qty)) + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip todel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#toTable");
            total += parseFloat(item_cost * item_qty);
            count += parseFloat(item_qty);
            an++;
            if(item_qty > item_rqty) 
                $('#row_' + row_no).addClass('danger'); 
            //else
                //$('#row_' + row_no).addClass('warning').next('tr').removeClass('warning');   
            
        });


        // Totals calculations after item addition
        var gtotal = total + product_tax + shipping;
        $('#total').text(formatMoney(total));
        $('#titems').text((an-1)+' ('+(parseFloat(count)-1)+')');
        if (site.settings.tax1) {
            $('#ttax1').text(formatMoney(product_tax));
        }
        $('#gtotal').text(formatMoney(gtotal));
        if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#toTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        //audio_success.play();
    }
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
function add_transfer_item(item) {

    if (count == 1) {
        toitems = {};
        if ($('#from_warehouse').val()) {
            $('#from_warehouse').select2("readonly", true);
        } else {
            bootbox.alert('Please select from warehouse');
            item = null;
            return;
        }
    }
    if (item == null) {
        return;
    }
    item_id = item.id;
    if (site.settings.item_addition == 1) {
        // if the item addition is on 
        if (toitems[item_id]) {
            toitems[item_id].row.qty += 1;
        } else {
            toitems[item_id] = item;
        }
    } else {
        // else - the item addition is off
        toitems[count] = item;
    }
    localStorage.setItem('toitems', JSON.stringify(toitems));
    loadItems();
    return true;
}

if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 1) {
            var message = "You will loss data!";
            return message;
        }
    });
}