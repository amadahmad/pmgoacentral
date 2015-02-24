$(document).ready(function () {

// Order level shipping and discoutn localStorage 
    if (qudiscount = localStorage.getItem('qudiscount')) {
        $('#qudiscount').val(qudiscount);
    }
    $('#qutax2').change(function (e) {
        localStorage.setItem('qutax2', $(this).val());
    });
    if (qutax2 = localStorage.getItem('qutax2')) {
        $('#qutax2').select2("val", qutax2);
    }
    $('#qustatus').change(function (e) {
        localStorage.setItem('qustatus', $(this).val());
    });
    if (qustatus = localStorage.getItem('qustatus')) {
        $('#qustatus').select2("val", qustatus);
    }
    var old_shipping;
    $('#qushipping').focus(function () {
        old_shipping = $(this).val();
    }).change(function () {
        if (!is_numeric($(this).val())) {
            $(this).val(old_shipping);
            bootbox.alert('Unexpected value provided!');
            return;
        } else {
            shipping = $(this).val() ? parseFloat($(this).val()) : '0';
        }
        localStorage.setItem('qushipping', shipping);
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#gtotal').text(formatMoney(gtotal));
    });
    if (qushipping = localStorage.getItem('qushipping')) {
        shipping = parseFloat(qushipping);
        $('#qushipping').val(shipping);
    }
    //$('#add_item').attr('required', 'required');
    //$('form[data-toggle="validator"]').bootstrapValidator('addField', 'add_item');

// If there is any item in localStorage
    if (localStorage.getItem('quitems')) {
        loadItems();
    }

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                if (localStorage.getItem('quitems')) {
                    localStorage.removeItem('quitems');
                }
                if (localStorage.getItem('qudiscount')) {
                    localStorage.removeItem('qudiscount');
                }
                if (localStorage.getItem('qutax2')) {
                    localStorage.removeItem('qutax2');
                }
                if (localStorage.getItem('qushipping')) {
                    localStorage.removeItem('qushipping');
                }
                if (localStorage.getItem('quref')) {
                    localStorage.removeItem('quref');
                }
                if (localStorage.getItem('quwarehouse')) {
                    localStorage.removeItem('quwarehouse');
                }
                if (localStorage.getItem('qunote')) {
                    localStorage.removeItem('qunote');
                }
                if (localStorage.getItem('qucustomer')) {
                    localStorage.removeItem('qucustomer');
                }
                if (localStorage.getItem('qucurrency')) {
                    localStorage.removeItem('qucurrency');
                }
                if (localStorage.getItem('qudate')) {
                    localStorage.removeItem('qudate');
                }
                if (localStorage.getItem('qustatus')) {
                    localStorage.removeItem('qustatus');
                }
                if (localStorage.getItem('qubiller')) {
                    localStorage.removeItem('qubiller');
                }
                
                $('#modal-loading').show();
                location.reload();
            }
        });
    });

// save and load the fields in and/or from localStorage

    $('#quref').change(function (e) {
        localStorage.setItem('quref', $(this).val());
    });
    if (quref = localStorage.getItem('quref')) {
        $('#quref').val(quref);
    }
    $('#quwarehouse').change(function (e) {
        localStorage.setItem('quwarehouse', $(this).val());
    });
    if (quwarehouse = localStorage.getItem('quwarehouse')) {
        $('#quwarehouse').select2("val", quwarehouse);
    }

    //$(document).on('change', '#qunote', function (e) {
    $('#qunote').redactor('destroy');
    $('#qunote').redactor({
        buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
        formattingTags: ['p', 'pre', 'h3', 'h4'],
        minHeight: 100,
        changeCallback: function (e) {
            var v = this.get();
            localStorage.setItem('qunote', v);
        }
    });
    if (qunote = localStorage.getItem('qunote')) {
        $('#qunote').redactor('set', qunote);
    }
    var $customer = $('#qucustomer');
    $customer.change(function (e) {
        localStorage.setItem('qucustomer', $(this).val());
        //$('#qucustomer_id').val($(this).val());
    });
    if (qucustomer = localStorage.getItem('qucustomer')) {
        $customer.val(qucustomer).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "customers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        if (count > 1) {
            $customer.select2("readonly", true);
            $customer.val(qucustomer);
            $('#quwarehouse').select2("readonly", true);
            //$('#qucustomer_id').val(qucustomer);
        }
    } else {
        nsCustomer();
    }
    /*
    $currency.change(function (e) {
        localStorage.setItem('qucurrency', $(this).val());
    });
    if (qucurrency = localStorage.getItem('qucurrency')) {
        $currency.val(qucurrency);
        //$currency.attr('disabled', 'disabled');
        $currency.select2("readonly", true);
    } else if (count > 1) {
        localStorage.setItem('qucurrency', DC);
    }

    $(document).on('change', '.rprice', function () { 
        var item_id = $(this).closest('tr').attr('data-item-id');
        quitems[item_id].row.net_price = $(this).val();
        localStorage.setItem('quitems', JSON.stringify(quitems));
    });*/


// prevent default action uqun enter
    $('body').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

// Order tax calcuation 
    if (site.settings.tax2 != 0) {
        $('#qutax2').change(function () {
            localStorage.setItem('qutax2', $(this).val());
            loadItems();
            return;
        });
    }

// Order discount calcuation 
    var old_qudiscount;
    $('#qudiscount').focus(function () {
        old_qudiscount = $(this).val();
    }).change(function () {
        if (is_valid_discount($(this).val())) {
            localStorage.removeItem('qudiscount');
            localStorage.setItem('qudiscount', $(this).val());
            loadItems();
            return;
        } else {
            $(this).val(old_qudiscount);
            bootbox.alert('Unexpected value provided!');
            return;
        }

    });


    /* ---------------------- 
     * Delete Row Method 
     * ---------------------- */
    /*$(document).on('click', '.qudel', function () {
     var row = $(this).closest('tr'), item_id = row.attr('data-item-id');
     delete quitems[item_id];
     row.remove();
     if(quitems.hasOwnProperty(item_id)) { } else {
     localStorage.setItem('quitems', JSON.stringify(quitems));
     loadItems();
     return;
     }
     });*/

    $(document).on('click', '.qudel', function () {
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        if (site.settings.product_discount == 1) {
            idiscount = formatMoney($.trim(row.children().children('.rdiscount').text()));
            total_discount -= idiscount;
        }
        if (site.settings.tax1 == 1) {
            var itax = row.children().children('.sproduct_tax').text();
            var iptax = itax.split(') ');
            var iproduct_tax = parseFloat(iptax[1]);
            product_tax -= iproduct_tax;
        }
        var iqty = parseFloat(row.children().children('.rquantity').val());
        var iprice = parseFloat(row.children().children('.rprice').val());
        an -= 1;
        total -= (iqty * iprice);
        count -= iqty;

        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#total').text(formatMoney(total));
        $('#tds').text(formatMoney(total_discount));
        $('#titems').text(count - 1);
        $('#ttax1').text(formatMoney(product_tax));
        $('#gtotal').text(formatMoney(gtotal));
        if (count == 1) {
            $('#qucustomer').select2('readonly', false);
            $('#quwarehouse').select2('readonly', false);
        }
        //console.log(quitems[item_id].row.name + ' is being removed.');
        delete quitems[item_id];
        localStorage.setItem('quitems', JSON.stringify(quitems));
        row.remove();

    });

    /* -----------------------
     * Edit Row Modal Hanlder 
     ----------------------- */
    $(document).on('click', '.edit', function () {
        var row = $(this).closest('tr');
        row_id = row.attr('id');
        item_id = row.attr('data-item-id');
        item = quitems[item_id];
        qty = row.children().children('.rquantity').val();
        price = row.children().children('.rprice').val();
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
        if (site.settings.tax1) {
            $('#ptax').text(item.tax_rate.name + ' (' + item.tax_rate.rate + ')');
            $('#old_tax').val($('#sproduct_tax_' + row_id).text());
        }
        if (site.settings.product_discount != 0) {
            $('#pserial').val(row.children().children('.rdiscount').val());
        }
        $('#pquantity').val(qty);
        $('#old_qty').val(qty);
        $('#pprice').val(price);
        $('#old_price').val(price);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pexpiry').val(row.children().children('.rexpiry').val());
        $('#pproduct_tax').select2('val', row.children().children('.rproduct_tax').val());
        $('#pdiscount').val(row.children().children('.rdiscount').val());
        $('#prModal').appendTo("body").modal('show');

    });

    /* -----------------------
     * Edit Row Method 
     ----------------------- */
    $(document).on('click', '#editItem', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id'), new_pr_tax = $('#pproduct_tax').val(), new_pr_tax_rate;
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        } else {
            new_pr_tax_rate = false;
        }

        quitems[item_id].row.qty = parseFloat($('#pquantity').val()),
        quitems[item_id].row.price = parseFloat($('#pprice').val()),
        quitems[item_id].row.tax_rate = new_pr_tax_rate,
        quitems[item_id].row.discount = $('#pdiscount').val(),
        quitems[item_id].row.tax_method = 1,
        quitems[item_id].row.expiry = $('#pexpiry').val();
        localStorage.setItem('quitems', JSON.stringify(quitems));
        $('#prModal').modal('hide');
        loadItems();
        return;
    });

    /* ------------------------------
     * Show manual item addition modal 
     ------------------------------- */
    $(document).on('click', '#addManually', function (e) {
        $('#mModal').appendTo("body").modal('show');
        return false;
    });
    
    $(document).on('click', '#addItemManually', function(e){
        var mid = (new Date).getTime(),
        mcode = $('#mcode').val(),
        mname = $('#mname').val(),
        mtax = parseInt($('#mtax').val()),
        mqty = parseFloat($('#mquantity').val()),
        mdiscount = $('#mdiscount').val() ? $('#mdiscount').val() : '0',
        mprice = parseFloat($('#mprice').val()),
        mtax_rate = {};
        $.each(tax_rates, function() {
            if (this.id == mtax) {
                mtax_rate = this;
            } 
        });
        quitems[mid] = {"id":mid,"label":mname+' ('+mcode+')',"row":{"id":mid,"code":mcode,"name":mname,"quantity":mqty,"price":mprice,"tax_rate":mtax, "qty":mqty,"discount":mdiscount}, "tax_rate":mtax_rate};
        localStorage.setItem('quitems', JSON.stringify(quitems));
        loadItems();
        $('#mModal').modal('hide');
        $('#mcode').val('');
        $('#mname').val('');
        $('#mtax').val('');
        $('#mquantity').val('');
        $('#mdiscount').val('');
        $('#mprice').val('');
        return false;
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
        quitems[item_id].row.qty = new_qty;
        localStorage.setItem('quitems', JSON.stringify(quitems));
        loadItems();
    });
    
    /* --------------------------
     * Edit Row Price Method 
     -------------------------- */
    var old_price;
    $(document).on("focus", '.rprice', function () {
        old_price = $(this).val();
    }).on("change", '.rprice', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_price);
            bootbox.alert('Unexpected value provided!');
            return;
        }
        var new_price = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
        quitems[item_id].row.price = new_price;
        localStorage.setItem('quitems', JSON.stringify(quitems));
        loadItems();
    });
    
    $(document).on("click", '#removeReadonly', function () { 
       $('#qucustomer').select2('readonly', false);
       //$('#quwarehouse').select2('readonly', false);
       return false;
    });
    
    
});
/* -----------------------
 * Misc Actions
 ----------------------- */

// hellper function for customer if no localStorage value
function nsCustomer() {
    $('#qucustomer').select2({
        minimumInputLength: 1,
        ajax: {
            url: site.base_url + "customers/suggestions",
            dataType: 'json',
            quietMillis: 15,
            data: function (term, page) {
                return {
                    term: term,
                    limit: 10
                };
            },
            results: function (data, page) {
                if (data.results != null) {
                    return {results: data.results};
                } else {
                    return {results: [{id: '', text: 'No Match Found'}]};
                }
            }
        }
    });
}
//localStorage.clear();
function loadItems() {

    if (localStorage.getItem('quitems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        total_discount = 0;
        $("#quTable tbody").empty();
        quitems = JSON.parse(localStorage.getItem('quitems'));
        $.each(quitems, function () {
            var item = this;
            var item_id = item.id;
            if (site.settings.item_addition == 1)
                quitems[item_id] = item;
            else
                quitems[count] = item;
            
            var item_price = item.row.price, item_qty = item.row.qty, item_aqty = item.row.quantity, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            
            var pr_tax = item.tax_rate, row_total;
            var pr_tax_val = 0, pr_tax_rate = 0;
            if (site.settings.tax1 == 1) {
                if (pr_tax !== false) {
                    row_total = item_price * item_qty;
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
                            pr_tax_val = (item_price * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                            pr_tax_rate = pr_tax.rate + '%';
                            item_price -= pr_tax_val;
                        } else {
                            pr_tax_val = ((item_price) * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = pr_tax.rate + '%';
                        }
                    }
                    product_tax += pr_tax_val * item_qty;
                }
            }

            var ds = item_ds;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    product_discount = parseFloat(((this.price * this.qty) * parseFloat(pds[0])) / 100);
                } else {
                    product_discount = parseFloat(ds);
                }
            } else {
                product_discount = parseFloat(ds);
            }
            total_discount += parseFloat(product_discount);
            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + (site.settings.item_addition == 1 ? item_id : count) + '" data-item-id="' + (site.settings.item_addition == 1 ? item_id : count) + '"></tr>');
            tr_html = '<td><input name="product_id[]" type="hidden" class="rid" value="' + item_id + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
            tr_html += '<td class="text-right"><input class="form-control input-sm text-right rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + formatMoney(item_price) + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(item_price) + '</span></td>';
            tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item_qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_discount == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0 - product_discount) + '</span></td>';
            }
            if (site.settings.tax1 == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }
            tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)) + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip pointer qudel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#quTable");
            total += parseFloat(item_price * item_qty);
            count += parseFloat(item_qty);
            an++;
            if(item_qty > item_aqty)
                $('#row_' + row_no).addClass('danger'); 
            //else
                //$('#row_' + row_no).addClass('warning').next('tr').removeClass('warning');   
            
        });
        // Order level discount calculations        
        if (qudiscount = localStorage.getItem('qudiscount')) {
            var ds = qudiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    order_discount = parseFloat(((total + product_tax) * parseFloat(pds[0])) / 100);
                } else {
                    order_discount = parseFloat(ds);
                }
            } else {
                order_discount = parseFloat(ds);
            }

            total_discount += parseFloat(order_discount);
        }

        // Order level tax calculations    
        if (site.settings.tax2 != 0) {
            if (qutax2 = localStorage.getItem('qutax2')) {
                $.each(tax_rates, function () {
                    if (this.id == qutax2) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                        }
                    }
                });
            }
        }

        // Totals calculations after item addition
        var gtotal = parseFloat(((total + product_tax + invoice_tax) - total_discount) + shipping);
        $('#total').text(formatMoney(total));
        $('#titems').text((an-1)+' ('+(parseFloat(count)-1)+')');
        $('#tds').text(formatMoney(total_discount));
        if (site.settings.tax1) {
            $('#ttax1').text(formatMoney(product_tax));
        }
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatMoney(invoice_tax));
        }
        $('#gtotal').text(formatMoney(gtotal));
        if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#quTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        //if(count > 1) {
        //    $('#add_item').removeAttr('required');
        //    $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        //}
        //audio_success.play();
    }
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
function add_quote_item(item) {

    if (count == 1) {
        quitems = {};
        if ($('#quwarehouse').val() && $('#qucustomer').val()) { 
            $('#qucustomer').select2("readonly", true);
            $('#quwarehouse').select2("readonly", true);
        } else {
            bootbox.alert('Please select customer/warehouse');
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
        if (quitems[item_id]) {
            quitems[item_id].row.qty += 1;
        } else {
            quitems[item_id] = item;
        }
    } else {
        // else - the item addition is off
        quitems[count] = item;
    }
    localStorage.setItem('quitems', JSON.stringify(quitems));
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