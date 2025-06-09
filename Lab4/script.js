document.addEventListener("DOMContentLoaded", () => {
  const table = document.getElementById("myTable"); 
  const headers = table.querySelectorAll("th"); 
  let sortDirections = Array(headers.length).fill(true);

  headers.forEach((header, columnIndex) => {
    header.addEventListener("click", () => {
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));

      const isAscending = sortDirections[columnIndex];
      rows.sort((a, b) => {
        const cellA = a.children[columnIndex].innerText.trim();
        const cellB = b.children[columnIndex].innerText.trim();

        let valueA, valueB;

        if (isNaN(cellA)) {
          valueA = cellA;
        } else {
          valueA = Number(cellA);
        }

        if (isNaN(cellB)) {
          valueB = cellB;
        } else {
          valueB = Number(cellB);
        }


        if (valueA < valueB) {
          if (isAscending) 
            return -1;
          else return 1;
        }
        
        if (valueA > valueB) {
          if (isAscending) {
            return 1;
          } else {
            return -1;
          }
        }
        
        return 0;
      });

      rows.forEach(row => tbody.appendChild(row));
      sortDirections[columnIndex] = !isAscending;
    });
  });
});
