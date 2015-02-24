<style>
	.table td:first-child { font-weight: bold; }
	label { margin-right: 10px; }
</style>
<div class="box">
	<div class="box-header">
		<h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('group_permissions'); ?></h2>
	</div>
	<div class="box-content">  
		<div class="row">            
			<div class="col-lg-12">

				<p class="introtext"><?php echo $this->lang->line("set_permissions"); ?></p>

				<?php if (!empty($p)) {
					if ($p->group_id != 1) {

						echo form_open("system_settings/permissions/" . $id); ?>
						<div class="table-responsive">
							<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

								<thead>
									<tr>
										<th colspan="6" class="text-center"><?php echo $group->description . ' ( ' . $group->name.' ) '.$this->lang->line("group_permissions"); ?></th>
									</tr>
									<tr>
										<th rowspan="2" class="text-center"><?php echo $this->lang->line("module_name"); ?>
										</th>
										<th colspan="5" class="text-center"><?php echo $this->lang->line("permissions"); ?></th>
									</tr>
									<tr>
										<th class="text-center"><?php echo $this->lang->line("view"); ?></th>
										<th class="text-center"><?php echo $this->lang->line("add"); ?></th>
										<th class="text-center"><?php echo $this->lang->line("edit"); ?></th>
										<th class="text-center"><?php echo $this->lang->line("delete"); ?></th>
										<th class="text-center"><?php echo $this->lang->line("misc"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $this->lang->line("products"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
										</td>
										<td>

										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("sales"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-index" <?php echo $p->{'sales-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-add" <?php echo $p->{'sales-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-edit" <?php echo $p->{'sales-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-delete" <?php echo $p->{'sales-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
											<input type="checkbox" value="1" id="sales-email" class="checkbox" name="sales-email" <?php echo $p->{'sales-email'} ? "checked" : ''; ?>><label for="sales-email" class="padding05"><?=lang('email')?></label>
											<input type="checkbox" value="1" id="sales-pdf" class="checkbox" name="sales-pdf" <?php echo $p->{'sales-pdf'} ? "checked" : ''; ?>><label for="sales-pdf" class="padding05"><?=lang('pdf')?></label>
											<?php if(POS) { ?>
											<input type="checkbox" value="1" id="pos-index" class="checkbox" name="pos-index" <?php echo $p->{'pos-index'} ? "checked" : ''; ?>><label for="pos-index" class="padding05"><?=lang('pos')?></label>
											<?php } ?>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("deliveries"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-deliveries" <?php echo $p->{'sales-deliveries'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-add_delivery" <?php echo $p->{'sales-add_delivery'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-edit_delivery" <?php echo $p->{'sales-edit_delivery'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-delete_delivery" <?php echo $p->{'sales-delete_delivery'} ? "checked" : ''; ?>>
										</td>
										<td>
											<!--<input type="checkbox" value="1" id="sales-email" class="checkbox" name="sales-email_delivery" <?php echo $p->{'sales-email_delivery'} ? "checked" : ''; ?>><label for="sales-email_delivery" class="padding05"><?=lang('email')?></label>-->
											<input type="checkbox" value="1" id="sales-pdf" class="checkbox" name="sales-pdf_delivery" <?php echo $p->{'sales-pdf_delivery'} ? "checked" : ''; ?>><label for="sales-pdf_delivery" class="padding05"><?=lang('pdf')?></label>
										</td>
									</tr>
									<tr>
										<td><?php echo $this->lang->line("gift_cards"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-gift_cards" <?php echo $p->{'sales-gift_cards'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-add_gift_card" <?php echo $p->{'sales-add_gift_card'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-edit_gift_card" <?php echo $p->{'sales-edit_gift_card'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="sales-delete_gift_card" <?php echo $p->{'sales-delete_gift_card'} ? "checked" : ''; ?>>
										</td>
										<td>

										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("quotes"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="quotes-index" <?php echo $p->{'quotes-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="quotes-add" <?php echo $p->{'quotes-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="quotes-edit" <?php echo $p->{'quotes-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="quotes-delete" <?php echo $p->{'quotes-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
											<input type="checkbox" value="1" id="quotes-email" class="checkbox" name="quotes-email" <?php echo $p->{'quotes-email'} ? "checked" : ''; ?>><label for="quotes-email" class="padding05"><?=lang('email')?></label>
											<input type="checkbox" value="1" id="quotes-pdf" class="checkbox" name="quotes-pdf" <?php echo $p->{'quotes-pdf'} ? "checked" : ''; ?>><label for="quotes-pdf" class="padding05"><?=lang('pdf')?></label>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("purchases"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="purchases-index" <?php echo $p->{'purchases-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="purchases-add" <?php echo $p->{'purchases-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="purchases-edit" <?php echo $p->{'purchases-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="purchases-delete" <?php echo $p->{'purchases-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
											<input type="checkbox" value="1" id="purchases-email" class="checkbox" name="purchases-email" <?php echo $p->{'purchases-email'} ? "checked" : ''; ?>><label for="purchases-email" class="padding05"><?=lang('email')?></label>
											<input type="checkbox" value="1" id="purchases-pdf" class="checkbox" name="purchases-pdf" <?php echo $p->{'purchases-pdf'} ? "checked" : ''; ?>><label for="purchases-pdf" class="padding05"><?=lang('pdf')?></label>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("transfers"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="transfers-index" <?php echo $p->{'transfers-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="transfers-add" <?php echo $p->{'transfers-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="transfers-edit" <?php echo $p->{'transfers-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="transfers-delete" <?php echo $p->{'transfers-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
											<input type="checkbox" value="1" id="transfers-email" class="checkbox" name="transfers-email" <?php echo $p->{'transfers-email'} ? "checked" : ''; ?>><label for="transfers-email" class="padding05"><?=lang('email')?></label>
											<input type="checkbox" value="1" id="transfers-pdf" class="checkbox" name="transfers-pdf" <?php echo $p->{'transfers-pdf'} ? "checked" : ''; ?>><label for="transfers-pdf" class="padding05"><?=lang('pdf')?></label>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("customers"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="customers-index" <?php echo $p->{'customers-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="customers-add" <?php echo $p->{'customers-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="customers-edit" <?php echo $p->{'customers-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="customers-delete" <?php echo $p->{'customers-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line("suppliers"); ?></td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="suppliers-index" <?php echo $p->{'suppliers-index'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="suppliers-add" <?php echo $p->{'suppliers-add'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="suppliers-edit" <?php echo $p->{'suppliers-edit'} ? "checked" : ''; ?>>
										</td>
										<td class="text-center">
											<input type="checkbox" value="1" class="checkbox" name="suppliers-delete" <?php echo $p->{'suppliers-delete'} ? "checked" : ''; ?>>
										</td>
										<td>
										</td>
									</tr>

								</tbody>
							</table>
						</div>  
						<div class="form-actions">
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
						<?php echo form_close();
					} else {
						echo $this->lang->line("group_x_allowed");
					}
				} else {
					echo $this->lang->line("group_x_allowed");
				} ?> 


			</div>
		</div>
	</div>
</div>