/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Datatables Js
 */

try{
 new simpleDatatables.DataTable("#datatable_1", {
    searchable: true,
    fixedHeight: false,
  })
} catch (err) {}
try{
  const dataTable_2 = new simpleDatatables.DataTable("#datatable_2")
  document.querySelector("button.csv").addEventListener("click", () => {
      dataTable_2.exportCSV({
          type:"csv",
          download: true,
          lineDelimiter: "\n\n",
          columnDelimiter: ";"
      })
  })
  document.querySelector("button.sql").addEventListener("click", () => {
      dataTable_2.export({
          type:"sql",
          download: true,
          tableName: "export_table"
      })
  })
  document.querySelector("button.txt").addEventListener("click", () => {
      dataTable_2.export({
          type:"txt",
          download: true,
      })
  })
  document.querySelector("button.json").addEventListener("click", () => {
      dataTable_2.export({
          type:"json",
          download: true,
          escapeHTML: true,
          space: 3
      })
  })
} catch (err) {}

try{
    document.addEventListener('DOMContentLoaded', function() {
        var checkedAll = document.querySelector("[name='select-all']"),
          checkedItems = document.querySelectorAll("[name='check']");
    
          checkedAll?.addEventListener('change', function() {
            var isChecked = checkedAll.checked;
            checkedItems.forEach(function(item) {
              item.checked = isChecked;
            });
          });
    
          checkedItems.forEach(function(item) {
            item.addEventListener('click', function() {
              var inputs = checkedItems.length;
              var inputsChecked = document.querySelectorAll("[name='check']:checked").length;
        
              if (inputsChecked <= 0) {
                checkedAll.checked = false;
                checkedAll.indeterminate = false;
              } else if (inputs === inputsChecked) {
                checkedAll.checked = true;
                checkedAll.indeterminate = false;
              } else {
                checkedAll.checked = true;
                checkedAll.indeterminate = true;
              }
            });
          });
           // Get all <th> elements within the <thead> of the table
          var thElements = document.querySelectorAll('table > thead > tr > th');
          var firstButton = th.querySelector('button:first-child');
          if (firstButton) {
            firstButton.classList.remove('datatable-sorter');
          }

          // // Iterate through each <th> element
          // thElements.forEach(function(th) {
          //     // Find the first button child of the <th>
          //     var firstButton = th.querySelector('button:first-child');
          //     // If a button is found, remove the class ".datatable-sorter"
          //     if (firstButton) {
          //         firstButton.classList.remove('datatable-sorter');
          //     }
          // });
        });

        document.querySelector(".checkbox-all thead tr th:first-child button").classList.remove('datatable-sorter');
} catch (err) {}