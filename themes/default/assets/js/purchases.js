$(document).ready(function () {

// Order level shipping and discoutn localStorage 
    if (podiscount = localStorage.getItem('podiscount')) {
        $('#podiscount').val(podiscount);
    }
    $('#potax2').change(function (e) {
        localStorage.setItem('potax2', $(this).val());
    });
    if (potax2 = localStorage.getItem('potax2')) {
        $('#potax2').select2("val", potax2);
    }
    $('#postatus').change(function (e) {
        localStorage.setItem('postatus', $(this).val());
    });
    if (postatus = localStorage.getItem('postatus')) {
        $('#postatus').select2("val", postatus);
    }
    var old_shipping;
    $('#poshipping').focus(function () {
        old_shipping = $(this).val();
    }).change(function () {
        if (!is_numeric($(this).val())) {
            $(this).val(old_shipping);
            bootbox.alert('Unexpected value provided!');
            return;
        } else {
            shipping = $(this).val() ? parseFloat($(this).val()) : '0';
        }
        localStorage.setItem('poshipping', shipping);
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#gtotal').text(formatMoney(gtotal));
    });
    if (poshipping = localStorage.getItem('poshipping')) {
        shipping = parseFloat(poshipping);
        $('#poshipping').val(shipping);
    }

// If there is any item in localStorage
    if (localStorage.getItem('poitems')) {
        loadItems();
    }

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                if (localStorage.getItem('poitems')) {
                    localStorage.removeItem('poitems');
                }
                if (localStorage.getItem('podiscount')) {
                    localStorage.removeItem('podiscount');
                }
                if (localStorage.getItem('potax2')) {
                    localStorage.removeItem('potax2');
                }
                if (localStorage.getItem('poshipping')) {
                    localStorage.removeItem('poshipping');
                }
                if (localStorage.getItem('poref')) {
                    localStorage.removeItem('poref');
                }
                if (localStorage.getItem('powarehouse')) {
                    localStorage.removeItem('powarehouse');
                }
                if (localStorage.getItem('ponote')) {
                    localStorage.removeItem('ponote');
                }
                if (localStorage.getItem('posupplier')) {
                    localStorage.removeItem('posupplier');
                }
                if (localStorage.getItem('pocurrency')) {
                    localStorage.removeItem('pocurrency');
                }
                if (localStorage.getItem('poextras')) {
                    localStorage.removeItem('poextras');
                }
                if (localStorage.getItem('podate')) {
                    localStorage.removeItem('podate');
                }
                if (localStorage.getItem('postatus')) {
                    localStorage.removeItem('postatus');
                }
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
                $('#modal-loading').show();
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
        if (count > 1) {
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
    } else if (count > 1) {
        localStorage.setItem('pocurrency', DC);
    }
    /*$('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        poitems[item_id].row.expiry = $(this).val();
        localStorage.setItem('poitems', JSON.stringify(poitems));
    });*/
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
    $(document).on('change', '.rexpiry', function () { 
        var item_id = $(this).closest('tr').attr('data-item-id');
        poitems[item_id].row.expiry = $(this).val();
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
            return;
        });
    }

// Order discount calcuation 
    var old_podiscount;
    $('#podiscount').focus(function () {
        old_podiscount = $(this).val();
    }).change(function () {
        if (is_valid_discount($(this).val())) {
            localStorage.removeItem('podiscount');
            localStorage.setItem('podiscount', $(this).val());
            loadItems();
            return;
        } else {
            $(this).val(old_podiscount);
            bootbox.alert('Unexpected value provided!');
            return;
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
        an -= 1;
        total -= (iqty * icost);
        count -= iqty;

        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
        $('#total').text(formatMoney(total));
        $('#tds').text(formatMoney(total_discount));
        $('#titems').text(count - 1);
        $('#ttax1').text(formatMoney(product_tax));
        $('#gtotal').text(formatMoney(gtotal));
        if (count == 1) {
            $('#posupplier').select2('readonly', false);
            //$('#pocurrency').select2('readonly', false);
        }
        //console.log(poitems[item_id].row.name + ' is being removed.');
        delete poitems[item_id];
        localStorage.setItem('poitems', JSON.stringify(poitems));
        row.remove();

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

        poitems[item_id].row.qty = parseFloat($('#pquantity').val()),
                poitems[item_id].row.cost = parseFloat($('#pcost').val()),
                poitems[item_id].row.tax_rate = new_pr_tax_rate,
                poitems[item_id].row.discount = $('#pdiscount').val(),
                poitems[item_id].row.tax_method = 1,
                poitems[item_id].row.expiry = $('#pexpiry').val();
        localStorage.setItem('poitems', JSON.stringify(poitems));
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
        poitems[item_id].row.qty = new_qty;
        localStorage.setItem('poitems', JSON.stringify(poitems));
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
        poitems[item_id].row.cost = new_cost;
        localStorage.setItem('poitems', JSON.stringify(poitems));
        loadItems();
    });
    
    $(document).on("click", '#removeReadonly', function () { 
       $('#posupplier').select2('readonly', false); 
       return false;
    });
    
    
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

    if (localStorage.getItem('poitems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        total_discount = 0;
        $("#poTable tbody").empty();
        poitems = JSON.parse(localStorage.getItem('poitems'));
        $.each(poitems, function () {
            var item = this;
            var item_id = item.id;
            if (site.settings.item_addition == 1)
                poitems[item_id] = item;
            else
                poitems[count] = item;
            
            var supplier = localStorage.getItem('posupplier'), belong = false;
            var item_cost = item.row.cost, item_qty = item.row.qty, item_bqty = item.row.quantity_balance, item_expiry = item.row.expiry, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            if(item_bqty == '') {
                if (supplier == item.row.supplier1) {
                    belong = true;
                    if ((item.row.supplier1price != null || item.row.supplier1price != '0' || item.row.supplier1price != '')) {
                        item_cost = item.row.supplier1price;
                    }
                }
                if (supplier == item.row.supplier2) {
                    belong = true;
                    if ((item.row.supplier2price != null || item.row.supplier2price != '0' || item.row.supplier2price != '')) {
                        item_cost = item.row.supplier2price;
                    }
                }
                if (supplier == item.row.supplier3) {
                    belong = true;
                    if ((item.row.supplier3price != null || item.row.supplier3price != '0' || item.row.supplier3price != '')) {
                        item_cost = item.row.supplier3price;
                    }
                }
                if (supplier == item.row.supplier4) {
                    belong = true;
                    if ((item.row.supplier4price != null || item.row.supplier4price != '0' || item.row.supplier4price != '')) {
                        item_cost = item.row.supplier4price;
                    }
                }
                if (supplier == item.row.supplier5) {
                    belong = true;
                    if ((item.row.supplier5price != null || item.row.supplier5price != '0' || item.row.supplier5price != '')) {
                        item_cost = item.row.supplier5price;
                    }
                }
            }
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

            var ds = item_ds;
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
            tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value="' + item_bqty + '"><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + item_qty + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_expiry == 1) {
                tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            }
            if (site.settings.product_discount == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0 - product_discount) + '</span></td>';
            }
            if (site.settings.tax1 == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }
            tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty)) + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#poTable");
            total += parseFloat(item_cost * item_qty);
            count += parseFloat(item_qty);
            an++;
            if(!belong) 
                $('#row_' + row_no).addClass('danger'); 
            //else
                //$('#row_' + row_no).addClass('warning').next('tr').removeClass('warning');   
            
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
            $("html, body").animate({scrollTop: $('#poTable').offset().top - 150}, 500);
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
function add_purchase_item(item) {

    if (count == 1) {
        poitems = {};
        if ($('#posupplier').val()) {
            //$('#pocurrency').val() &&
            //$('#pocurrency').select2("readonly", true);
            $('#posupplier').select2("readonly", true);
        } else {
            bootbox.alert('Please select supplier');
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
        if (poitems[item_id]) {
            poitems[item_id].row.qty += 1;
        } else {
            poitems[item_id] = item;
        }
    } else {
        // else - the item addition is off
        poitems[count] = item;
    }
    localStorage.setItem('poitems', JSON.stringify(poitems));
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