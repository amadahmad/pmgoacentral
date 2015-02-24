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
    if (poitems = JSON.parse(localStorage.getItem('poitems'))) {
        loadItems();
    }
    
    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                if(localStorage.getItem('poitems')) { localStorage.removeItem('poitems'); }
                if(localStorage.getItem('podiscount')) { localStorage.removeItem('podiscount'); }
                if(localStorage.getItem('potax2')) { localStorage.removeItem('potax2'); }
                if(localStorage.getItem('poshipping')) { localStorage.removeItem('poshipping'); }
                if(localStorage.getItem('poref')) { localStorage.removeItem('poref'); }
                if(localStorage.getItem('powarehouse')) { localStorage.removeItem('powarehouse'); }
                if(localStorage.getItem('ponote')) { localStorage.removeItem('ponote'); }
                if(localStorage.getItem('posupplier')) { localStorage.removeItem('posupplier'); }
                if(localStorage.getItem('pocurrency')) { localStorage.removeItem('pocurrency'); }
                if(localStorage.getItem('poextras')) { localStorage.removeItem('poextras'); }
                if(localStorage.getItem('podate')) { localStorage.removeItem('podate'); }
                /*total = 0; count = 0;product_tax = 0; invoice_tax = 0; total_discount = 0;
                $('#posupplier').select2('readonly', false);
                $('#pocurrency').select2('readonly', false);
                $('#ponote').redactor('set', '');
                $('#poTable tbody').empty();
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
        $('#supplier_id').val($(this).val());
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
            $supplier.select2("readonly", true);
            $supplier.val(posupplier);
            $('#supplier_id').val(posupplier);
        }
    } else {
        nsSupplier();
    }
    
    $currency.change(function (e) {
        localStorage.setItem('pocurrency', $(this).val());
    });
    if (pocurrency = localStorage.getItem('pocurrency')) {
        $currency.val(pocurrency);
        //$currency.attr('disabled', 'disabled');
        $currency.select2("readonly", true);
    } else if (count > 0) {
        localStorage.setItem('pocurrency', DC);
    }
    $('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        poitems[item_id].expiry = $(this).val();
        localStorage.setItem('poitems', JSON.stringify(poitems));
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
        poitems[item_id].expiry = $(this).val();
        localStorage.setItem('poitems', JSON.stringify(poitems));
    });
    

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
    });
} 

// Order discount calcuation 
$('#podiscount').change(function () {
        localStorage.setItem('podiscount', $(this).val());
        loadItems();
    });
    

/* ---------------------- 
 * Delete Row Method 
 * ---------------------- */
/*$(document).on('click', '.podel', function () {
    var row = $(this).closest('tr'), item_id = row.attr('data-item-id');
    delete poitems[item_id];
    $('#modal-loading').show();
    //row.remove();
    localStorage.setItem('poitems', JSON.stringify(poitems));
    location.reload();
});*/

$(document).on('click', '.podel', function () {
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
    var icost = parseFloat(row.children().children('.rcost').val());
    an -= iqty; total -= (iqty*icost); count -= iqty;
    
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#total').text(formatMoney(total));
    $('#tds').text(formatMoney(total_discount));
    $('#titems').text(count);
    $('#ttax1').text(formatMoney(product_tax));
    $('#gtotal').text(formatMoney(gtotal));
    if(count == 0) { 
    $('#posupplier').select2('readonly', false);
                $('#pocurrency').select2('readonly', false);
    }
    console.log(poitems[item_id].name + ' is being removed.');
    delete poitems[item_id];
    localStorage.setItem('poitems', JSON.stringify(poitems));
    row.remove();
    //loadItems();
});
   
/* -----------------------
 * Edit Row Modal Hanlder 
 ----------------------- */
$(document).on('click', '.edit', function () {
    var row = $(this).closest('tr');
    row_id = row.attr('id');
    item_id = row.attr('data-item-id');
    item = poitems[item_id];
    qty = row.children().children('.rquantity').val();
    cost = row.children().children('.rcost').val();
    $('#prModalLabel').text(item.name + ' (' + item.code + ')');
    if (site.settings.tax1) {
        $('#ptax').text(item.tax_rate.name + ' (' + item.tax_rate.rate + ')');
        $('#old_tax').val($('#sproduct_tax_' + row_id).text());
    }
    if (site.settings.product_discount != 0) {
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
    $('#pdiscount').val(row.children().children('.rdiscount').val());
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

    poitems[item_id].qty = parseFloat($('#pquantity').val()),
    poitems[item_id].cost = parseFloat($('#pcost').val()),
    poitems[item_id].tax_rate = new_pr_tax_rate,
    poitems[item_id].discount = $('#pdiscount').val(),
    poitems[item_id].tax_method = 1,
    poitems[item_id].expiry = $('#pexpiry').val();
    localStorage.setItem('poitems', JSON.stringify(poitems));
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
    var row = $(this).closest('tr');
    if(!is_numeric($(this).val())) { $(this).val(old_qty); bootbox.alert('Script might have bugs!'); return; }
    var new_qty = parseFloat($(this).val()),
    item_id = row.attr('data-item-id');
    poitems[item_id].qty = new_qty;
    localStorage.setItem('poitems', JSON.stringify(poitems));
    loadItems();    
});
/*var old_qty;
$(document).on("focus", '.rquantity', function () {
    old_qty = $(this).val();
}).on("change", '.rquantity', function () {
    var row = $(this).closest('tr');
    if(!is_numeric($(this).val())) { $(this).val(old_qty); bootbox.alert('Script might have bugs!'); return; }
    var new_qty = parseFloat($(this).val()),
    item_id = row.attr('data-item-id');
    var item = poitems[item_id];
    var cost = parseFloat(row.children().children('.rcost').val());
    //product discount calculations        
    if (site.settings.product_discount == 1) {
            var new_ds = 0, ds = parseFloat(row.children().children('.rdiscount').val());
            var old_ds = parseFloat(row.children().children('.sdiscount').text());
            if(ds) {
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                        new_ds = (total * parseFloat(pds[0])) / 100;
                } else {
                    new_ds = parseFloat(ds);
                }
            } else {
                new_ds = parseFloat(ds);
            }
            
            total_discount += old_ds;
            total_discount += new_ds;
        }
    } 
    // product tax calculations  
    if (site.settings.tax1 == 1) {
        if (item.tax_rate.type == 1) {
            var itax = row.children().children('.sproduct_tax').text(),
            iptax = itax.split(') '),
            old_tax = parseFloat(iptax[1]),
            new_tax = parseFloat((((cost-new_ds) * new_qty) * item.tax_rate.rate) / 100),
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
    count -= old_qty;
    count += new_qty;
    poitems[item_id].qty = new_qty;
    localStorage.setItem('poitems', JSON.stringify(poitems));
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    row.children().children('.rcost').val(cost);
    row.children().children('scost').text(cost);
    row.children().children('.ssubtotal').text(formatMoney((cost * new_qty) + new_tax));
    $('#total').text(formatMoney(total));
    $('#titems').text(formatMoney(count));
    $('#tds').text(formatMoney(total_discount));
    if (site.settings.tax2 == 1) {
        $('#ttax2').text(formatMoney(invoice_tax));
    }
    $('#gtotal').text(formatMoney(gtotal));
});
*/
});
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
    total = 0; count = 0; product_tax = 0; invoice_tax = 0; total_discount = 0;
    $("#poTable tbody").empty();
    // if the item addition is on 
 
            $.each(poitems, function () {

                item = this;
                item_id = item.id;
                if (site.settings.item_addition == 1)
                    poitems[item_id] = item;
                else
                    poitems[count] = item;
                
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
                        product_discount = ((this.cost * this.qty) * parseFloat(pds[0])) / 100;
                    } else {
                        product_discount = parseFloat(ds);
                    }
                } else {
                    product_discount = parseFloat(ds);
                }
                total_discount += product_discount;

                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + (site.settings.item_addition == 1 ? item_id : count) + '" data-item-id="' + (site.settings.item_addition == 1 ? item_id : count) + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0-product_discount) + '</span></td>';
                }
                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item.qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#poTable");
                total += parseFloat(item_cost * item.qty);
                
                count += parseFloat(item.qty);
                an++;
                $('#row_' + row_no).addClass('warning').next('tr').removeClass('warning');
            });

        // Order level discount calculations        
        if (podiscount = localStorage.getItem('podiscount')) {
            var ds = podiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                        order_discount = ((total + product_tax) * parseFloat(pds[0])) / 100;
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
            if (potax2 = localStorage.getItem('potax2')) {
                $.each(tax_rates, function () {
                    if (this.id == potax2) {
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
        if(count > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#poTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        audio_success.play();

}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
/*function add_purchase_item(item) {
    if (item == null) {
        return false;
    }
    
    item_id = item.id;

    if (site.settings.item_addition == 1) {
        // if the item addition is on 
        if (poitems[item_id]) {
            poitems[item_id].qty += 1;
        } else {
            poitems[item_id] = item;
        }
        localStorage.setItem('poitems', JSON.stringify(poitems));
    } else {
        // else - the item addition is off
        poitems[count] = item;
        localStorage.setItem('poitems', JSON.stringify(poitems));
    }
    loadItems();
    
}*/

function add_purchase_item(item) {
    if (item == null) {
        return false;
    }
    if (count == 0) {
        poitems = {};
        if ($('#pocurrency').val() && $('#posupplier').val()) {
            //$('#pocurrency').attr('disabled', 'disabled');
            $('#pocurrency').select2("readonly", true);
            $('#posupplier').select2("readonly", true);
        } else {
            bootbox.alert('Please select curreny/supplier');
            item = null;
            return false;
        }
    }
    item_id = item.id;
    
    if (site.settings.item_addition == 1) {
        // if the item addition is on 
        if (poitems[item_id]) {
            poitems[item_id].qty += 1;
        } else {
            poitems[item_id] = item;
        }
    } else {
        poitems[count] = item;
    }
        localStorage.setItem('poitems', JSON.stringify(poitems));
        total = 0; count = 0; product_tax = 0; invoice_tax = 0; total_discount = 0;
        $("#poTable tbody").empty();
        $.each(poitems, function () {
            item = this;
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
                    product_discount = ((this.cost * this.qty) * parseFloat(pds[0])) / 100;
                } else {
                    product_discount = parseFloat(ds);
                }
            } else {
                product_discount = parseFloat(ds);
            }

            total_discount += product_discount;

            var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + (site.settings.item_addition == 1 ? item.id : count) + '" data-item-id="' + (site.settings.item_addition == 1 ? item.id : count) + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + formatMoney(item_cost) + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item.qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item.expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }    
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0-product_discount) + '</span></td>';
                }
            if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item.qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#poTable");
            total += parseFloat(item_cost * item.qty);
            count += parseFloat(item.qty);
            an++;
            $('#row_' + row_no).addClass('warning').next('tr').removeClass('warning');
        });

        

        
    /*} else {
        // else - the item addition is off
        poitems[count] = item;
        localStorage.setItem('poitems', JSON.stringify(poitems));
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
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item.discount + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0-product_discount) + '</span></td>';
                }
        if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right rproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right rsubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item.qty)) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#poTable");

        //item_cost += pr_discount;
        total += parseFloat(item_cost);
        
        $('.row_' + count).addClass('warning');
        $('.row_' + (count - 1)).removeClass('warning');
        
        count++;
        an++;

    } */

    // Order level discount calculations        
    if (podiscount = localStorage.getItem('podiscount')) {
        var ds = podiscount;
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                    order_discount = ((total + product_tax) * parseFloat(pds[0])) / 100;
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
        if (potax2 = localStorage.getItem('potax2')) {
            $.each(tax_rates, function () {
                if (this.id == potax2) {
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
    if(scroll == 1 &&count > site.settings.bc_fix && site.settings.bc_fix != 0) {
        $("html, body").animate({scrollTop: $('#poTable').offset().top - 150}, 500);
        $(window).scrollTop($(window).scrollTop() + 1);
    }
    audio_success.play();
    return true;
}

if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 0) {
            var message = "You will loss data!";
            return message;
        }
    });
}