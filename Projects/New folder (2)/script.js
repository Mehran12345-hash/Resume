const apiUrl = "https://raw.githubusercontent.com/Mehran12345-hash/Data2/refs/heads/main/data.json";

async function fetchCategories() {
  try {
    const response = await fetch(apiUrl);
    const data = await response.json();
    displayCategories(data.categories);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

function displayCategories(categories) {
  const categoriesContainer = document.getElementById("categories");
  categories.forEach(category => {
    const categoryDiv = document.createElement("div");
    categoryDiv.classList.add("category");
    categoryDiv.innerHTML = `
      <h2>${category.name}</h2>
      <p>${category.description}</p>
    `;
    categoriesContainer.appendChild(categoryDiv);
  });
}

fetchCategories();
