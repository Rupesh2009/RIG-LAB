/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * File: Editable Js
 */



var datatable = new DataTable("table", {
	plugins: {
		editable: {
			enabled: true,
			contextMenu: true,
			hiddenColumns: true,
			menuItems: [
				{
					text: "<i class='iconoir-edit-pencil fs-18 text-muted me-1'></i> Edit Cell",
					action: function() {
						this.editCell();
					}
				},
				{
					text: "<i class='iconoir-edit-pencil fs-18 text-muted me-1'></i> Edit Row",
					action: function() {
						this.editRow();
					}
				},			
				{
					separator: true
				},
				{
					text: "<i class='iconoir-xmark-circle fs-18 text-muted me-1'></i> Remove",
					action: function() {
						if ( confirm("Are you sure?") ) {
							this.removeRow();
						}
					}
				}
			]
		}
	}
});

