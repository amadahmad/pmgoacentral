// Set item object
var items = {};

$(document).ready(function () {

// Order level shipping and discoutn localStorage 
    $('#podiscount').change(function (e) {
        localStorage.setItem('podiscount', $(this).val());
    });
    if (podiscount = localStorage.getItem('podiscount')) {
        $('#podiscount').val(podiscount);
    }
    $('#potax2').change(function (e) {
        localStorage.setItem('potax2', $(this).val());
    });
    if (potax2 = localStorage.getItem('potax2')) {
        $('#potax2').select2("val", potax2);
    }
    $('#poshipping').change(function () {
        shipping = parseFloat($(this).val());
        localStorage.setItem('poshipping', shipping);
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#gtotal').text(formatMoney(gtotal));
    });
    if (poshipping = localStorage.getItem('poshipping')) {
        shipping = parseFloat(poshipping);
        $('#poshipping').val(shipping);
    }

// If there is any item in localStorage
    if (polsitems = JSON.parse(localStorage.getItem('polsitems'))) {
        loadItems();
    }
    
    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                localStorage.clear();
                location.reload();
            }
        });
    });

// save and load the fields in and/or from localStorage
    var $supplier = $('#posupplier'), $currency = $('#pocurrency');

    $('#poref').change(function (e) {
        localStorage.setItem('poref', $(this).val());
    });
    if (poref = localStorage.getItem('poref')) {
        $('#poref').val(poref);
    }
    $('#powarehouse').change(function (e) {
        localStorage.setItem('powarehouse', $(this).val());
    });
    if (powarehouse = localStorage.getItem('powarehouse')) {
        $('#powarehouse').select2("val", powarehouse);
    }
    
    //$(document).on('change', '#ponote', function (e) {
    $('#ponote').redactor('destroy');
    $('#ponote').redactor({
        buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
        formattingTags: ['p', 'pre', 'h3', 'h4'],
        minHeight: 100,
        changeCallback: function (e) {
            var v = this.get();
            localStorage.setItem('ponote', v);
        }
    });
    if (ponote = localStorage.getItem('ponote')) {
        $('#ponote').redactor('set', ponote);
    }
    $supplier.change(function (e) {
        localStorage.setItem('posupplier', $(this).val());
    });
    if (posupplier = localStorage.getItem('posupplier')) {
        $supplier.val(posupplier).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "suppliers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "suppliers/suggestions",
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
        if (count > 0) {
            $supplier.select2("enable", false);
        }
    } else {
        nsSupplier();
    }
    if (count > 0) {
        localStorage.setItem('pocurrency', DC);
    }
    $currency.change(function (e) {
        localStorage.setItem('pocurrency', $(this).val());
    });
    if (pocurrency = localStorage.getItem('pocurrency')) {
        $currency.val(pocurrency);
        $currency.attr('disabled', 'disabled');
    }
    $('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        items[item_id].expiry = $(this).val();
        localStorage.setItem('polsitems', JSON.stringify(items));
    });
    if (localStorage.getItem('poextras')) {
        $('#extras').iCheck('check');
        $('#extras-con').show();
    }
    $('#extras').on('ifChecked', function () {
        localStorage.setItem('poextras', 1);
        $('#extras-con').slideDown();
    });
    $('#extras').on('ifUnchecked', function () {
        localStorage.removeItem("poextras");
        $('#extras-con').slideUp();
    });
    $('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        items[item_id].expiry = $(this).val();
        localStorage.setItem('polsitems', JSON.stringify(items));
    });
    
});
/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
function add_purchase_item(item) {
    if (item == null) {
        return false;
    }
    if (count == 0) {
        if ($('#pocurrency').val()) {
            $('#pocurrency').attr('disabled', 'disabled');
        } else {
            bootbox.alert('Please select curreny');
            item = null;
            return false;
        }
        if ($('#posupplier').val()) {
            $('#posupplier').select2("enable", false);
        } else {
            bootbox.alert('Please select supplier');
            item = null;
            return false;
        }
    }
    item_id = item.id;

    if (site.settings.item_addition == 1) {
        // if the item addition is on 
        if (items[item_id]) {
            items[item_id].qty += 1;
        } else {
            items[item_id] = item;
        }

        localStorage.setItem('polsitems', JSON.stringify(items));
        total = 0; count = 0;product_tax = 0; invoice_tax = 0; total_discount = 0;
        $("#inTable tbody").empty();
        $.each(items, function () {
            item = this;
            item_cost = item.cost;
            item_code = item.code;
            item_name = item.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");

            var pr_tax = item.tax_rate, row_total;
            var pr_tax_val = 0, pr_tax_rate = 0;
            /* if (site.settings.tax1) {
             if (this.tax_rate) {
             if (this.tax_rate.type == 2) {
             pr_tax_rate = parseFloat(this.tax_rate.rate);
             }
             if (this.tax_rate.type == 1) {
             pr_tax_rate = ((this.cost*this.qty) * parseFloat(this.tax_rate.rate)) / 100;
             }
             product_tax += pr_tax_rate;
             } else {
             pr_tax_rate = 0;
             }
             } else {
             pr_tax_rate = 0;
             }*/

            if (site.settings.tax1 == 1) {
                if (pr_tax !== false) {
                    row_total = item.cost * item.qty;
                    if (pr_tax.type == 2) {
                        if (item.tax_method == '0') {
                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate;
                            row_total -= pr_tax_val;
                        } else {
                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate * item.qty;
                        }
                    }
                    if (pr_tax.type == 1) {
                        if (item.tax_method == '0') {
                            pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                            //pr_tax_val = (row_total * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = pr_tax.rate + '%';
                            item_cost -= pr_tax_val;
                        } else {
                            pr_tax_val = ((item_cost) * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = pr_tax.rate + '%';
                        }
                    }
                    product_tax += pr_tax_val * item.qty;
                }
            }

            var ds = this.discount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    if (site.settings.discount_method == 1) {
                        product_discount = ((this.cost * this.qty) * parseFloat(pds[0])) / 100;
                    }
                    if (site.settings.discount_method == 2) {
                        product_discount = (((this.cost * this.qty) + pr_tax_rate) * parseFloat(pds[0])) / 100;
                    }
                } else {
                    product_discount = parseFloat(ds);
                }
            } else {
                product_discount = parseFloat(ds);
            }

            total_discount += product_discount;

            var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item.id + '" data-item-id="' + item_id + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }    
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount" id="sdiscount_' + row_no + '">' + formatMoney(product_discount) + '</span></td>';
                }
            if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item.qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#inTable");
            total += parseFloat(item_cost * item.qty);
            count += item.qty;
            an++;
        });
        $('.row_' + item_id).addClass('warning');
        
    } else {
        // else - the item addition is off
        items[count] = item;
        localStorage.setItem('polsitems', JSON.stringify(items));
        item_cost = item.cost;
        item_code = item.code;
        item_name = item.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
        pr_tax = item.tax_rate;
        var pr_tax_val = 0, pr_tax_rate = 0;
        if (site.settings.tax1 == 1) {
            if (pr_tax !== false) {
                if (pr_tax.type == 2) {
                    if (item.tax_method == '0') {
                        pr_tax_val = parseFloat(pr_tax.rate);
                        pr_tax_rate = pr_tax.rate;
                        item_cost -= pr_tax_val;
                    } else {
                        pr_tax_val = parseFloat(pr_tax.rate);
                        pr_tax_rate = pr_tax.rate;
                    }
                }
                if (pr_tax.type == 1) {
                    if (item.tax_method == '0') {
                        pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                        pr_tax_rate = pr_tax.rate + '%';
                        item_cost -= pr_tax_val;
                    } else {
                        pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / 100;
                        pr_tax_rate = pr_tax.rate + '%';
                    }
                }
                product_tax += pr_tax_val;
            }
        }

        var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + count + '" data-item-id="' + count + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="scost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="squantity_' + row_no + '" onClick="this.select();"></td>';
        if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }        
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount" id="sdiscount_' + row_no + '">' + formatMoney(product_discount) + '</span></td>';
                }
        if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right rproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right rsubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#inTable");

        //item_cost += pr_discount;
        total += parseFloat(item_cost);
        
        $('.row_' + count).addClass('warning');
        $('.row_' + (count - 1)).removeClass('warning');
        
        count++;
        an++;

    }

    // Start Order Calculations
    if (site.settings.tax2 != 0) {
        var inv_tax = $('#potax2').val();
        $.each(tax_rates, function () {
            if (this.id == inv_tax) {
                if (this.type == 2) {
                    invoice_tax = parseFloat(this.rate);
                }
                if (this.type == 1) {
                    invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                }
            }
        });
    }

    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#titems').text(count);
    $('#total').text(total.toFixed(2));
    $('#tds').text(formatMoney(total_discount));
    if (site.settings.tax1) {
        $('#ttax1').text(formatMoney(product_tax));
    }
    if (site.settings.tax2 != 0) {
        $('#ttax2').text(formatMoney(invoice_tax));
    }
    $('#gtotal').text(formatMoney(gtotal));

    $('.tip').tooltip();
    if (count > site.settings.bc_fix && site.settings.bc_fix != 0) {
        $("html, body").animate({scrollTop: $('#inTable').offset().top - 60}, 500);
        $(window).scrollTop($(window).scrollTop() + 1);
    }

    audio_success.play();
    return true;
}


// prevent default action upon enter
$('body').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});

// Order tax calcuation 
if (site.settings.tax2 != 0) {
    $('#potax2').change(function () {
        localStorage.setItem('potax2', $(this).val());
        loadItems();
        /*    if($('#podiscount').val()) {
                var ds = $('#podiscount').val();
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        if (site.settings.discount_method == 1) {
                            order_discount = (total * parseFloat(pds[0])) / 100;
                        }
                        if (site.settings.discount_method == 2) {
                            order_discount = ((total + invoice_tax) * parseFloat(pds[0])) / 100;
                        }
                    } else {
                        order_discount = parseFloat(ds);
                    }
                } else {
                    order_discount = parseFloat(ds);
                }
                total_discount += order_discount;
            }
            */
           /* var inv_tax = $(this).val();
            $.each(tax_rates, function () {
                if (this.id == inv_tax) {
                    if (this.type == 2) {
                        invoice_tax = parseFloat(this.rate);
                    }
                    if (this.type == 1) {
                        if (site.settings.discount_method == 1) {
                            invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                        }
                        if (site.settings.discount_method == 2) {
                            invoice_tax = parseFloat(((total + product_tax) * this.rate) / 100);
                            if($('#podiscount').val()) {
                                var ds = $('#podiscount').val();
                                if (ds.indexOf("%") !== -1) {
                                    var pds = ds.split("%");
                                    if (!isNaN(pds[0])) {
                                        if (site.settings.discount_method == 1) {
                                            order_discount = (total * parseFloat(pds[0])) / 100;
                                        }
                                        if (site.settings.discount_method == 2) {
                                            order_discount = ((total + invoice_tax) * parseFloat(pds[0])) / 100;
                                        }
                                    } else {
                                        order_discount = parseFloat(ds);
                                    }
                                } else {
                                    order_discount = parseFloat(ds);
                                }
                                total_discount += order_discount;
                            }
                        }
                    }
                }
            });

            $('#ttax2').text(formatMoney(invoice_tax));
            $('#ttax2').text(formatMoney(invoice_tax));
            var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
            $('#gtotal').text(formatMoney(gtotal));
*/
    });
} 
// Order discount calcuation 
var old_discount_val;
    $('#podiscount').focus(function () { 
        old_discount_val = $(this).val() ? $(this).val() : '0'; 
    }).change(function () {
        // Order discoutn calculation - calculated the old discount
        var ods = old_discount_val;
        if (ods.indexOf("%") !== -1) {
            var pods = ods.split("%");
            if (!isNaN(pods[0])) {
                if (site.settings.discount_method == 1) {
                    old_order_discount = (total * parseFloat(pods[0])) / 100;
                }
                if (site.settings.discount_method == 2) {
                    old_order_discount = ((total + product_tax + invoice_tax) * parseFloat(pods[0])) / 100;
                }
            } else {
                old_order_discount = parseFloat(ds);
            }
        } else {
            old_order_discount = parseFloat(ods);
        }
        // Order discoutn calculation - calculated the new discount
        var ds = $(this).val() ? $(this).val() : '0';
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                if (site.settings.discount_method == 1) {
                    order_discount = (total * parseFloat(pds[0])) / 100;
                }
                if (site.settings.discount_method == 2) {
                    order_discount = ((total + product_tax + invoice_tax) * parseFloat(pds[0])) / 100;
                }
            } else {
                order_discount = parseFloat(ds);
            }
        } else {
            order_discount = parseFloat(ds);
        }
        
        total_discount -= old_order_discount;
        total_discount += order_discount;
        
        // Order discoutn calculation - if apply before tax then calculate tax
        if (site.settings.tax2 != 0 && $('#potax2').val() && site.settings.discount_method == 1) {
            var inv_tax = $('#potax2').val();
            $.each(tax_rates, function () {
                if (this.id == inv_tax) {
                    if (this.type == 2) {
                        invoice_tax = parseFloat(this.rate);
                    }
                    if (this.type == 1) {
                        invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                    }
                }
            });
        }    
        
        $('#tds').text(formatMoney(total_discount));
        $('#ttax2').text(formatMoney(invoice_tax));
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#gtotal').text(formatMoney(gtotal));
        
    }); 
// Order tax calcautaion
if (site.settings.tax2 == 1) {
    $('#potax2').change(function () {
        var inv_tax = $(this).val();
        var ds = $('#podiscount').val();
        $.each(tax_rates, function () {
            if (this.id == inv_tax) {
                if (!ds) {
                    // Order tax calcautaion - if no dicount applied
                    if (this.type == 2) {
                        invoice_tax = parseFloat(this.rate);
                    }
                    if (this.type == 1) {
                        invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                    }
                } else {
                    // Order tax calcautaion - if dicount applied and method before tax
                    if (site.settings.discount_method == 1) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat(((total + product_tax - total_discount) * this.rate) / 100);
                        }
                    }
                    // Order tax calcautaion - if dicount applied and method after tax
                    if (site.settings.discount_method == 2) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat(((total + product_tax) * this.rate) / 100);
                        }
                        if (ds.indexOf("%") !== -1) {
                            var pds = ds.split("%");
                            if (!isNaN(pds[0])) {
                                total_discount = ((total + product_tax + invoice_tax) * parseFloat(pds[0])) / 100;
                            } else {
                                total_discount = parseFloat(ds);
                            }
                        } else {
                            total_discount = parseFloat(ds);
                        }
                    }
                }
            }
        });
        
        $('#tds').text(total_discount.toFixed(2));
        $('#ttax2').text(invoice_tax.toFixed(2));
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#gtotal').text(parseFloat(gtotal).toFixed(2));
    });
}

/* ---------------------- 
 * Delete Row Method 
 * ---------------------- */
$(document).on('click', '.podel', function () {
    var row = $(this).closest('tr');
    var item_id = row.attr('data-item-id');
    /*if (site.settings.product_discount == 1) {
        idiscount = formatMoney($.trim(row.children().children('.rproduct_discount').text()));
    }
    if (site.settings.tax1 == 1) {
        itax = formatMoney($.trim(row.children().children('.rproduct_tax').text()));
    }
    iqty = parseFloat(row.children().children('.rquantity').val());
    icost = parseFloat(row.children().children('.rcost').val());
    itax = row.children().children('.sproduct_tax').text();
    iptax = itax.split(') ');
    iproduct_tax = parseFloat(iptax[1]);
    an -= iqty; total -= (iqty*icost);
    product_tax -= iproduct_tax;
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#total').text(formatMoney(total));
    $('#ttax1').text(formatMoney(product_tax));
    $('#gtotal').text(formatMoney(gtotal));
    $('#titems').text(an);
    console.log(items[item_id].name + ' is being removed.');*/
    delete items[item_id];
    localStorage.setItem('polsitems', JSON.stringify(items));
    row.remove();
    loadItems();
});

/* -----------------------
 * Edit Row Modal Hanlder 
 ----------------------- */
$(document).on('click', '.edit', function () {
    var row = $(this).closest('tr');
    row_id = row.attr('id');
    item_id = row.attr('data-item-id');
    item = items[item_id];
    qty = row.children().children('.rquantity').val();
    cost = row.children().children('.rcost').val();
    $('#prModalLabel').text(item.name + ' (' + item.code + ')');
    if (site.settings.tax1) {
        $('#ptax').text(item.tax_rate.name + ' (' + item.tax_rate.rate + ')');
        $('#old_tax').val($('#sproduct_tax_' + row_id).text());
    }
    if (site.settings.product_discount) {
        $('#pserial').val(row.children().children('.rdiscount').val());
    }
    $('#pquantity').val(qty);
    $('#old_qty').val(qty);
    $('#pcost').val(cost);
    $('#old_cost').val(cost);
    $('#row_id').val(row_id);
    $('#item_id').val(item_id);
    $('#pexpiry').val(row.children().children('.rexpiry').val());
    $('#pproduct_tax').select2('val', row.children().children('.rproduct_tax').val());
    $('#pdiscount').val(row.children().children('.sdiscount').val() ? row.children().children('.sdiscount').val() : 0);
    $('#prModal').appendTo("body").modal('show');
    
});

/* -----------------------
 * Edit Row Method 
 ----------------------- */
$(document).on('click', '#editItem', function () {
    var row = $('#'+$('#row_id').val());
    var item_id = row.attr('data-item-id'), new_pr_tax = $('#pproduct_tax').val(), new_pr_tax_rate;
    if(new_pr_tax){
    $.each(tax_rates, function () {
            if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
        });
    } else {
        new_pr_tax_rate = false;
    }

    items[item_id].qty = parseFloat($('#pquantity').val()),
    items[item_id].cost = parseFloat($('#pcost').val()),
    items[item_id].tax_rate = new_pr_tax_rate,
    items[item_id].discount = $('#pdiscount').val(),
    items[item_id].tax_method = 1,
    items[item_id].expiry = $('#pexpiry').val();
    localStorage.setItem('polsitems', JSON.stringify(items));
    loadItems();
    
    $('#prModal').modal('hide');
});


/* ------------------------------
 * Show manual item addition modal 
 ------------------------------- */
$(document).on('click', '#addManually', function (e) {
    $('#mModal').appendTo("body").modal('show');
    return false;
});

/* --------------------------
 * Edit Row Quantity Method 
 -------------------------- */
$(document).on("change", '.rquantity', function () {
    var row = $(this).closest('tr'), new_qty = $(this).val() ? $(this).val() : 1,
    item_id = row.attr('data-item-id');
    items[item_id].qty = new_qty;
    localStorage.setItem('polsitems', JSON.stringify(items));
    loadItems();    
});
/*var old_qty;
$(document).on("focus", 'input[id^="quantity_"]', function () {
    old_qty = $(this).val();
});
$(document).on("blur", 'input[id^="quantity_"]', function () {
    var row = $(this).closest('tr'), new_qty = $(this).val(),
    item_id = row.attr('data-item-id'),
    item = items[item_id];

    cost = parseFloat(row.children().children('.rcost').val());
    if (site.settings.tax1 == 1) {
        if (item.tax_rate.type == 1) {
            var old_tax = parseFloat(((cost * old_qty) * item.tax_rate.rate) / 100),
                    new_tax = parseFloat(((cost * new_qty) * item.tax_rate.rate) / 100),
                    tax_rate = item.tax_rate.rate + '%';
        }
        if (item.tax_rate.type == 2) {
            var old_tax = old_qty * item.tax_rate.rate,
                    new_tax = new_qty * item.tax_rate.rate,
                    tax_rate = item.tax_rate.rate;
        }
        product_tax -= old_tax;
        product_tax += new_tax;
        row.children().children('.sproduct_tax').text((tax_rate ? '(' + tax_rate + ') ' : '') + formatMoney(new_tax));
        $('#ttax1').text(formatMoney(product_tax));
    }

    total -= cost * old_qty;
    total += cost * new_qty;
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    row.children().children('.rcost').val(cost);
    row.children().children('scost').text(cost);
    row.children().children('.ssubtotal').text(formatMoney((cost * new_qty) + new_tax));
    $('#total').text(formatMoney(total));
    if (site.settings.tax2 == 1) {
        $('#ttax2').text(formatMoney(invoice_tax));
    }
    $('#gtotal').text(formatMoney(gtotal));
});
*/
/* -----------------------
 * Misc Actions
 ----------------------- */

// hellper function for supplier if no localStorage value
function nsSupplier() {
    $('#posupplier').select2({
        minimumInputLength: 1,
        ajax: {
            url: site.base_url + "suppliers/suggestions",
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

function loadItems() {
    // if the item addition is on 
        if (site.settings.item_addition == 1) {
            total = 0; count = 0;product_tax = 0; invoice_tax = 0; total_discount = 0;
            $("#inTable tbody").empty();

            $.each(polsitems, function () {

                item = this;
                item_id = item.id;
                items[item_id] = item;
                item_cost = item.cost;
                item_code = item.code;
                item_name = item.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");

                var pr_tax = item.tax_rate, row_total;
                var pr_tax_val = 0, pr_tax_rate = 0;

                if (site.settings.tax1 == 1) {
                    if (pr_tax !== false) {
                        row_total = item.cost * item.qty;
                        if (pr_tax.type == 2) {
                            if (item.tax_method == '0') {
                                pr_tax_val = parseFloat(pr_tax.rate);
                                pr_tax_rate = pr_tax.rate;
                                row_total -= pr_tax_val;
                            } else {
                                pr_tax_val = parseFloat(pr_tax.rate);
                                pr_tax_rate = pr_tax.rate * item.qty;
                            }
                        }
                        if (pr_tax.type == 1) {
                            if (item.tax_method == '0') {
                                pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                                //pr_tax_val = (row_total * parseFloat(pr_tax.rate)) / 100;
                                pr_tax_rate = pr_tax.rate + '%';
                                item_cost -= pr_tax_val;
                            } else {
                                pr_tax_val = ((item_cost) * parseFloat(pr_tax.rate)) / 100;
                                pr_tax_rate = pr_tax.rate + '%';
                            }
                        }
                        product_tax += pr_tax_val * item.qty;
                    }
                }

                var ds = this.discount;
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        if (site.settings.discount_method == 1) {
                            product_discount = ((this.cost * this.qty) * parseFloat(pds[0])) / 100;
                        }
                        if (site.settings.discount_method == 2) {
                            product_discount = (((this.cost * this.qty) + pr_tax_rate) * parseFloat(pds[0])) / 100;
                        }
                    } else {
                        product_discount = parseFloat(ds);
                    }
                } else {
                    product_discount = parseFloat(ds);
                }
                total_discount += product_discount;

                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item.id + '" data-item-id="' + item_id + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount" id="sdiscount_' + row_no + '">' + formatMoney(product_discount) + '</span></td>';
                }
                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item.qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#inTable");
                total += parseFloat(item_cost * item.qty);
                
                count += item.qty;
                an++;
            });
            $('.row_' + item_id).addClass('warning');
            
        } else {

            // else - the item addition is off
            $.each(polsitems, function () {

                item = this;
                item_id = item.id;
                items[count] = item;
                item_cost = item.cost;
                item_code = item.code;
                item_name = item.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
                pr_tax = item.tax_rate;
                var pr_tax_val = 0, pr_tax_rate = 0;
                if (site.settings.tax1 == 1) {
                    if (pr_tax !== false) {
                        if (pr_tax.type == 2) {
                            if (item.tax_method == '0') {
                                pr_tax_val = parseFloat(pr_tax.rate);
                                pr_tax_rate = pr_tax.rate;
                                item_cost -= pr_tax_val;
                            } else {
                                pr_tax_val = parseFloat(pr_tax.rate);
                                pr_tax_rate = pr_tax.rate;
                            }
                        }
                        if (pr_tax.type == 1) {
                            if (item.tax_method == '0') {
                                pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                                pr_tax_rate = pr_tax.rate + '%';
                                item_cost -= pr_tax_val;
                            } else {
                                pr_tax_val = (item_cost * parseFloat(pr_tax.rate)) / 100;
                                pr_tax_rate = pr_tax.rate + '%';
                            }
                        }
                        product_tax += pr_tax_val;
                    }
                }

                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + count + '" data-item-id="' + count + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="scost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="squantity_' + row_no + '" onClick="this.select();"></td>';
                if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount" id="sdiscount_' + row_no + '">' + formatMoney(product_discount) + '</span></td>';
                }
                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right rproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right rsubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#inTable");

                //item_cost += pr_discount;
                total += parseFloat(item_cost);

                $('.row_' + count).addClass('warning');
                $('.row_' + (count - 1)).removeClass('warning');
                
                count++;
                an++;
            });
        }

        // Order level discount calculations        
        if (podiscount = localStorage.getItem('podiscount')) {
            var ds = podiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    if (site.settings.discount_method == 1) {
                        order_discount = ((total + product_tax) * parseFloat(pds[0])) / 100;
                    }
                    if (site.settings.discount_method == 2) {
                        order_discount = ((total + product_tax + invoice_tax) * parseFloat(pds[0])) / 100;
                    }
                } else {
                    order_discount = parseFloat(ds);
                }
            } else {
                order_discount = parseFloat(ds);
            }

            total_discount += order_discount;
        }
        
        // Order level tax calculations    
        if (site.settings.tax2 != 0) {
            if (potax = localStorage.getItem('potax')) {
                $.each(tax_rates, function () {
                    if (this.id == potax) {
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
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#total').text(formatMoney(total));
        $('#titems').text(count);
        $('#tds').text(formatMoney(total_discount));
        if (site.settings.tax1) {
            $('#ttax1').text(formatMoney(product_tax));
        }
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatMoney(invoice_tax));
        }
        $('#gtotal').text(formatMoney(gtotal));

        if (count > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#inTable').offset().top - 60}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        audio_success.play();
}

if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 0) {
            var message = "You will loss data!";
            return message;
        }
    });
}