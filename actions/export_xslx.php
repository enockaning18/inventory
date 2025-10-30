<!-- JavaScript Export Logic -->
<script>
document.getElementById("exportExcel").addEventListener("click", function() {
    
    let table = document.getElementsByClassName("reportTable")[0].outerHTML;
    let filename = document.getElementById("reportCaption").textContent.trim().replace(/\s+/g, '_') + ".xls";
    let data = `
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:x="urn:schemas-microsoft-com:office:excel"
          xmlns="http://www.w3.org/TR/REC-html40">
    <head>
      <meta charset="UTF-8">
      <title>Export</title>
    </head>
    <body>
      ${table}
    </body>
    </html>`;
  
  let blob = new Blob([data], { type: "application/vnd.ms-excel" });
  let url = URL.createObjectURL(blob);
  let a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
});
</script>
