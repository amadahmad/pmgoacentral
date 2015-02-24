var items = {};
function add_invoice_item(item) {
    if(item == null) { return false; }
    if(count == 1) {
        if($('#currency').val()) { $('#currency').attr('disabled', 'disabled'); } else { bootbox.alert('Please select curreny'); item = null; return false; }
        if($('#warehouse').val()) { $('#warehouse').attr('disabled', 'disabled'); } else { bootbox.alert('Please select warehouse'); item = null; return false; } 
        if($('#customer').val()) { $('#customer').select2("enable", false); } else { bootbox.alert('Please select customer'); item = null; return false; } 
    }
    item_id = item.id;
    
    if(site.settings.item_addition == 1) {
        if(items[item_id]) {
            items[item_id].qty = items[item_id].qty+1;
        } else {
            items[item_id] = item;
        }
        total = 0; product_tax = 0; invoice_tax = 0; total_discount = 0;
        $("#inTable tbody").empty();
        $.each(items, function() {
            if (site.settings.tax1) {
                if (this.tax_rate) {
                    if (this.tax_rate.type == 2) {
                        pr_tax_rate = parseFloat(this.tax_rate.rate);
                    }
                    if (this.tax_rate.type == 1) {
                        pr_tax_rate = ((this.price*this.qty) * parseFloat(this.tax_rate.rate)) / 100;
                    }
                    product_tax += pr_tax_rate;
                } else {
                    pr_tax_rate = 0;
                }
            } else {
                pr_tax_rate = 0;
            }

            var ds = this.discount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    if(site.settings.discount_method == 1) {
                        product_discount = ((this.price*this.qty) * parseFloat(pds[0])) / 100;
                    }
                    if(site.settings.discount_method == 2) {
                        product_discount = (((this.price*this.qty)+pr_tax_rate) * parseFloat(pds[0])) / 100;
                    }
                } else {
                    product_discount = parseFloat(ds);
                }
            } else {
                product_discount = parseFloat(ds);
            }
            
            total_discount += product_discount; 
        
            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="item_'+this.id+'"></tr>');
            tr_html = '<td><input name="product[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="'+this.id+'" title="Edit" style="cursor:pointer;"></i></td>';
            if (site.settings.product_serial) {
                tr_html += '<td><input class="form-control input-sm" name="product_serial[]" id="product_serial_' + row_no + '" type="text" value=""></td>';
            }
            if (site.settings.product_discount != 0) {
                tr_html += '<td class="text-right"><input class="form-control input-sm" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="'+this.discount+'"><span class="text-right" id="sdiscount_' + row_no + '">'+product_discount.toFixed(2)+'</span></td>';
            }
            if (site.settings.tax1 != 0) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="'+this.tax_rate.id+'"><span class="text-right" id="sproduct_tax_' + row_no + '">'+pr_tax_rate.toFixed(2)+'</span></td>';
            }
            tr_html += '<td><input class="form-control text-center" name="quantity[]" type="text" value="'+this.qty+'" data-id="'+row_no+'" data-item="'+this.id+'" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            tr_html += '<td class="text-right"><input class="form-control input-sm text-right price" name="unit_price[]" type="hidden" id="price_' + row_no + '" value="' + this.price + '"><span class="text-right" id="sprice_' + row_no + '">'+this.price+'</span></td>';
            tr_html += '<td class="text-right"><span class="text-right" id="subtotal_' + row_no + '">'+(this.price*this.qty).toFixed(2)+'</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#inTable");
            total += parseFloat(this.price*this.qty);

        });
        $('.item_' + item_id).addClass('warning');
    } else {
        
        items[item_id] = item;
        item_price = item.price;
        item_code = item.code;
        item_name = item.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
        pr_tax = item.tax_rate;
        if (site.settings.tax1) {
            if (pr_tax) {
                if (pr_tax.type == 2) {
                    pr_tax_rate = parseFloat(pr_tax.rate);
                }
                if (pr_tax.type == 1) {
                    pr_tax_rate = (item_price * parseFloat(pr_tax.rate)) / 100;
                }
                product_tax += pr_tax_rate;
            } else {
                pr_tax_rate = 0;
            }
        } else {
            pr_tax_rate = 0;
        }

        var row_no = (new Date).getTime();
        var newTr = $('<tr id="row_' + row_no + '" class="row_' + count + '"></tr>');
        tr_html = '<td><input name="product[]" type="hidden" value="' + item_code + '"><span id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> <i class="fa fa-edit tip edit" id="' + row_no + '" data-item="'+item_id+'" title="Edit" style="cursor:pointer;"></i></td>';
        if (site.settings.product_serial == 1) {
            tr_html += '<td><input class="form-control input-sm" name="product_serial[]" id="product_serial_' + row_no + '" type="text" value=""></td>';
        }
        if (site.settings.product_discount == 1) {
            tr_html += '<td class="text-right"><input class="form-control input-sm" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value=""><span class="text-right" id="sdiscount_' + row_no + '">0.00</span></td>';
        }
        if (site.settings.tax1 == 1) {
            tr_html += '<td class="text-right"><input class="form-control input-sm text-right" name="produict_tax[]" type="hidden" id="product_tax_' + row_no + '" value="'+pr_tax.id+'"><span class="text-right" id="sproduct_tax_' + row_no + '">'+pr_tax_rate.toFixed(2)+'</span></td>';
        }
        tr_html += '<td><input class="form-control text-center" name="quantity[]" type="text" value="'+item.qty+'" data-id="'+row_no+'" data-item="'+item_id+'" id="quantity_' + row_no + '" onClick="this.select();"></td>';
        tr_html += '<td class="text-right"><input class="form-control input-sm text-right price" name="unit_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '"><span class="text-right" id="sprice_' + row_no + '">'+parseFloat(item_price).toFixed(2)+'</span></td>';
        tr_html += '<td class="text-right"><span class="text-right" id="subtotal_' + row_no + '">'+(item_price*item.qty).toFixed(2)+'</span></td>';
        tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
        newTr.html(tr_html);
        newTr.prependTo("#inTable");

        //item_price += pr_discount;
        total += parseFloat(item_price);

        $('.row_' + count).addClass('warning');
        $('.row_' + (count-1)).removeClass('warning');
    
    }
    if (site.settings.tax2 != 0) {
        var inv_tax = $('#tax2').val();
        $.each(tax_rates, function() {
            if (this.id == inv_tax) {
                if (this.type == 2) {
                    invoice_tax = parseFloat(this.rate);
                }
                if (this.type == 1) {
                    invoice_tax = parseFloat(((total+product_tax-total_discount) * this.rate) / 100);
                }
            } 
        });
    }
    
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#total').text(total.toFixed(2));
    $('#tds').text(total_discount.toFixed(2));
    if (site.settings.tax1) {
        $('#ttax1').text(product_tax.toFixed(2));
    }
    if (site.settings.tax2 != 0) {
        $('#ttax2').text(invoice_tax.toFixed(2));
    }
    $('#gtotal').text(parseFloat(gtotal).toFixed(2));
    
    $('.tip').tooltip();
    if(count > site.settings.bc_fix && site.settings.bc_fix != 0) {
        $("html, body").animate({scrollTop: $('#inTable').offset().top - 60}, 500);
        $(window).scrollTop($(window).scrollTop() + 1);
    }
    $('#total_items').val(an);
    count++;
    an++;
    audio_success.play();
    return true;
}

function editInvoiceItem() {
$('body').bind('keypress', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});
if (site.settings.tax2 == 1) {
    $('#tax2').change(function(){
        var inv_tax = $(this).val();
        var ds = $('#discount').val();
        $.each(tax_rates, function() {
            if (this.id == inv_tax) {
                if(!ds) {
                    if (this.type == 2) {
                        invoice_tax = parseFloat(this.rate);
                    }
                    if (this.type == 1) {
                        invoice_tax = parseFloat(((total+product_tax-total_discount) * this.rate) / 100);
                    }
                } else {
                    if (ds.indexOf("%") !== -1) {
                        var pds = ds.split("%");
                        if (!isNaN(pds[0])) {
                            if(site.settings.discount_method == 1) {
                                total_discount = ((total+product_tax) * parseFloat(pds[0])) / 100;
                            }
                            if(site.settings.discount_method == 2) {
                                total_discount = ((total+product_tax+invoice_tax) * parseFloat(pds[0])) / 100;
                            }
                        } else {
                            total_discount = parseFloat(ds);
                        }
                    } else {
                        total_discount = parseFloat(ds);
                    }
                    
                    if (this.type == 2) {
                        invoice_tax = parseFloat(this.rate);
                    }
                    
                    if (this.type == 1) {
                        invoice_tax = parseFloat(((total+product_tax-total_discount) * this.rate) / 100);
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
$('#discount').change(function(){
        var ds = $(this).val();
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                if(site.settings.discount_method == 1) {
                    total_discount = ((total+product_tax) * parseFloat(pds[0])) / 100;
                }
                if(site.settings.discount_method == 2) {
                    total_discount = ((total+product_tax+invoice_tax) * parseFloat(pds[0])) / 100;
                }
            } else {
                total_discount = parseFloat(ds);
            }
        } else {
            total_discount = parseFloat(ds);
        }
        $('#tds').text(total_discount.toFixed(2));
        var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
         $('#gtotal').text(parseFloat(gtotal).toFixed(2));
    });
    
$(document).on('click', '.edit', function(){    
    item_id = $(this).attr('data-item');
    row_id = $(this).attr('id');
    item = items[item_id];
    qty = $('#quantity_'+row_id).val();
    price = $('#price_'+row_id).val();
    $('#prModalLabel').text(item.name +' ('+ item.code +')');
    if(site.settings.tax1) { $('#ptax').text(item.tax_rate.name +' ('+item.tax_rate.rate+')');
    $('#old_tax').val($('#sproduct_tax_'+row_id).text()); }
    if(site.settings.product_serial) { $('#pserial').val($('#product_serial_'+row_id).val()); }
    $('#pquantity').val(qty);
    $('#old_qty').val(qty);
    $('#pprice').val(price);
    $('#old_price').val(price);
    $('#row_id').val(row_id);
    $('#pdiscount').val($('#discount_'+row_id).val());
    //$('#prModal').css('top', '8%').modal({backdrop: false, show: true});
    $('#prModal').appendTo("body").modal('show');
    /*e.preventDefault();
    $(this).popover({html: true, placement: 'right', trigger: 'focus',
        content: function() {
            return $('#pr_popover_content').html();
        }
    }).popover('toggle');*/ 
});

$(document).on('click', '#editInvoiceItem', function(){
    var row_id = $('#row_id').val();
    item = items[item_id];
    new_price = parseFloat($('#pprice').val());
    old_price = parseFloat($('#old_price').val());
    new_qty = parseFloat($('#pquantity').val());
    old_qty = parseFloat($('#old_qty').val());
    items[item_id].qty = new_qty;
    
    if(site.settings.tax1 == 1) {
    old_tax = parseFloat($('#old_tax').val());
        if (item.tax_rate.type == 2) {
            new_tax = parseFloat(item.tax_rate.rate);
        }
        if (item.tax_rate.type == 1) {
            new_tax = parseFloat(((new_price*new_qty) * item.tax_rate.rate) / 100);
        }
        product_tax -= old_tax; 
        product_tax += new_tax; 
        $('#sproduct_tax_'+row_id).text(new_tax.toFixed(2)); 
        $('#ttax1').text(product_tax.toFixed(2));
    }
    
    if(site.settings.product_discount == 1) {
        var old_product_discount = parseFloat($('#sdiscount_'+row_id).text());
        var ds = $('#pdiscount').val();
        items[item_id].discount = ds;
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                if(site.settings.discount_method == 1) {
                    product_discount = ((new_price*new_qty) * parseFloat(pds[0])) / 100;
                }
                if(site.settings.discount_method == 2) {
                    product_discount = (((new_price*new_qty)+new_tax) * parseFloat(pds[0])) / 100;
                }
            } else {
                product_discount = parseFloat(ds);
            }
        } else {
            product_discount = parseFloat(ds);
        }
        total_discount -= old_product_discount;
        total_discount += product_discount;
        $('#discount_'+row_id).val(ds);
        $('#sdiscount_'+row_id).text(product_discount.toFixed(2));
        $('#tds').text(total_discount.toFixed(2));
    }
    
    if(site.settings.product_serial == 1) { $('#product_serial_'+row_id).val($('#pserial').val()); }
    $('#quantity_'+row_id).val(new_qty);
    total -= old_price*old_qty;
    total += new_price*new_qty;
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#price_'+row_id).val(new_price);
    $('#sprice_'+row_id).text(new_price.toFixed(2));
    $('#subtotal_'+row_id).text((new_price*new_qty).toFixed(2));
    $('#total').text(total.toFixed(2));
    if (site.settings.tax2 == 1) {
        $('#ttax2').text(invoice_tax.toFixed(2));
    }
    $('#gtotal').text(parseFloat(gtotal).toFixed(2));
    $('#prModal').modal('hide');
});

$(document).on('click', '#addManually', function(e){
    $('#mModal').appendTo("body").modal('show');
    return false;
});

$(document).on('click', '#addItemManually', function(e){
    var mid = (new Date).getTime(),
    mcode = $('#mcode').val(),
    mname = $('#mname').val(),
    mtax = $('#mtax').val(),
    mqty = $('#mquantity').val(),
    mserial = $('#mserial').val(),
    mdiscount = $('#mdiscount').val(),
    mprice = $('#mprice').val()
    mtax_rate = {};
    $.each(tax_rates, function() {
        if (this.id == mtax) {
	    mtax_rate = this;
	} 
    });
    items[mid] = {"id":mid,"label":mname,"code":mcode,"qty":mqty,"price":mprice,"name":mname,"tax_rate":mtax_rate,"discount":mdiscount};
    var row = add_invoice_item(items[mid]);
    if (row) 
	audio_success.play();
    $('#mModal').modal('hide');
    return false;
});

$(document).on("focus", 'input[id^="quantity_"]', function() {
    old_qty = $(this).val();
});
$(document).on("blur", 'input[id^="quantity_"]', function() {
    var row_id = $(this).attr('data-id');
    var item_id = $(this).attr('data-item');
    item = items[item_id];
    new_qty = $(this).val();
    price = parseFloat($('#price_'+row_id).val());
    if(site.settings.tax1 == 1) {
        if (item.tax_rate.type == 1) {
            old_tax = ((price*old_qty) * item.tax_rate.rate) / 100;
            new_tax = ((price*new_qty) * item.tax_rate.rate) / 100;
        }
        product_tax -= old_tax; 
        product_tax += new_tax; 
        $('#sproduct_tax_'+row_id).text(new_tax.toFixed(2)); 
        $('#ttax1').text(product_tax.toFixed(2));
    }
    total -= price*old_qty;
    total += price*new_qty;
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#price_'+row_id).val(price);
    $('#sprice_'+row_id).text(price.toFixed(2));
    $('#subtotal_'+row_id).text((price*new_qty).toFixed(2));
    $('#total').text(total.toFixed(2));
    if (site.settings.tax2 == 1) {
        $('#ttax2').text(invoice_tax.toFixed(2));
    }
    $('#gtotal').text(parseFloat(gtotal).toFixed(2));
});

    $('#currency').change(function(){
        $('.currency').text($(this).val());
    });
$(window).bind('beforeunload', function(e) {
    if(count > 1){
	var message = "You will loss data!";
	return message;
    }
});   
}