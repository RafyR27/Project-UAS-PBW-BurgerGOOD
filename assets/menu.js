const btnAddCard = document.getElementById("btn-add-cart");
const modal = document.getElementById("staticBackdrop");

let count = 1;

let addProduct = [];

let selectedData = null;

let stock_large = 0;
let stock_small = 0;

let type = "small";

document.addEventListener("DOMContentLoaded", function () {
  addProduct = JSON.parse(localStorage.getItem("cart")) || [];


  const detailButtons = document.querySelectorAll(".btn-detail");

  detailButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const radios = document.querySelectorAll('input[name="size"]');
      const priceEl = document.getElementById("detailPrice");

      count = 1;
      modal.querySelector(".quantity").innerText = count;

      const basePrice = Number(this.dataset.price);

      radios.forEach((radio) => {
        radio.checked = radio.value === "small";
      });

      selectedData = {
        name: this.dataset.name,
        type,
        quantity: count,
        unitPrice: basePrice,
        price: basePrice,
      };

      priceEl.innerText = "Rp. " + basePrice.toLocaleString("id-ID");

      radios.forEach((radio) => {
        radio.onchange = function () {
          let finalPrice = basePrice;

          type = this.value;

          if (type === "large" && count > stock_large) {
            count = stock_large;
            modal.querySelector(".quantity").innerText = count;
            selectedData.quantity = count;
          }

          if (type === "small" && count > stock_small) {
            count = stock_small;
            modal.querySelector(".quantity").innerText = count;
            selectedData.quantity = count;
          }

          if (type === "large") {
            finalPrice += 10000;
          }

          selectedData.type = type;
          selectedData.unitPrice = finalPrice;
          selectedData.price = finalPrice * selectedData.quantity;

          priceEl.innerText =
            "Rp. " + selectedData.price.toLocaleString("id-ID");
        };
      });
    });
  });

  updateCartDisplay();
});

function showDetail(name, description, image, price, stockSmall, stockLarge) {
  document.getElementById("detailName").innerText = name;
  document.getElementById("detailDescription").innerText = description;
  document.getElementById("detailImage").src =
    "data:image/jpeg;base64," + image;
  document.getElementById("detailPrice").innerText =
    "Rp. " + price.toLocaleString("id-ID");

  stock_large = stockLarge;
  stock_small = stockSmall;

  const sizeOptions = document.getElementById("sizeOptions");
  sizeOptions.innerHTML = "";

  if (stockSmall > 0) {
    sizeOptions.innerHTML += `
      <div class="form-check fredoka-font w-100">
        <input class="form-check-input" type="radio" name="size" id="sizeSmall" value="small" checked>
        <label class="form-check-label d-flex justify-content-between w-100" for="sizeSmall">
          <p>Small | Stock : ${stock_small}</p>
          <p>Free</p>
        </label>
      </div>
    `;
  }

  if (stockLarge > 0) {
    sizeOptions.innerHTML += `
      <div class="form-check fredoka-font w-100">
        <input class="form-check-input" type="radio" name="size" id="sizeLarge" value="large">
        <label class="form-check-label d-flex justify-content-between w-100" for="sizeLarge">
          <p>Large | Stock : ${stock_large}</p>
          <p>+ Rp. 10.000</p>
        </label>
      </div>
    `;
  }
}

const addCount = () => {
  if (type === "large" && count === stock_large) {
    return;
  }

  if (type === "small" && count === stock_small) {
    return;
  }  

  count++;

  modal.querySelector(".quantity").innerText = count;

  if (selectedData) {
    selectedData.quantity = count;
    selectedData.price = selectedData.unitPrice * count;

    document.getElementById("detailPrice").innerText =
      "Rp. " + selectedData.price.toLocaleString("id-ID");
  }
};

const delCount = () => {  
  if (count > 1) {
    count--;

    modal.querySelector(".quantity").innerText = count;

    if (selectedData) {
      selectedData.quantity = count;
      selectedData.price = selectedData.unitPrice * count;

      document.getElementById("detailPrice").innerText =
        "Rp. " + selectedData.price.toLocaleString("id-ID");
    }
  }
};

const addCountCard = (name, stockLarge, stockSmall) => {
  if (addProduct.length > 0) {
    addProduct = addProduct.map((item) => {
      if (name === item.name) {
        if(item.type === "small" && item.quantity === stockSmall){
          return item;
        }

        if(item.type === "large" && item.quantity === stockLarge){
          return item;
        }
        const newQuantity = item.quantity + 1;

        return {
          ...item,
          quantity: newQuantity,
          price: item.unitPrice * newQuantity,
        };
      }

      return item;
    });

    localStorage.setItem("cart", JSON.stringify(addProduct));
    updateCartDisplay();
  }
};

const delCountCard = (name) => {
  if (addProduct.length > 0) {
    addProduct = addProduct
      .map((item) => {
        if (name === item.name) {
          const newQuantity = item.quantity - 1;

          return {
            ...item,
            quantity: newQuantity,
            price: item.unitPrice * newQuantity,
          };
        }

        return item;
      })
      .filter((item) => item.quantity > 0);

    localStorage.setItem("cart", JSON.stringify(addProduct));
    updateCartDisplay();
  }
};

const updateCartDisplay = () => {
  const cards = document.querySelectorAll(".menu-card");

  cards.forEach((card) => {
    const name = card.dataset.name;
    const product = addProduct.find((item) => item.name === name);

    if (product) {
      card.querySelector(".quantity").innerHTML = product.quantity;
      card.querySelector(".btn-card").classList.remove("d-none");
      card.querySelector(".btn-detail").classList.add("d-none");
    } else {
      card.querySelector(".btn-card").classList.add("d-none");
      card.querySelector(".btn-detail").classList.remove("d-none");
    }
  });

  if (addProduct.length > 0) {
    document.getElementById("bar-checkout").classList.remove("d-none");

    let totalPrice = 0;
    let totalQuantity = 0;

    addProduct.forEach((item) => {
      totalPrice += item.price;
      totalQuantity += item.quantity;
    });

    document.getElementById("bar-item").innerText = totalQuantity + " Item";
    document.getElementById("bar-price").innerHTML =
      "Rp. " +
      totalPrice.toLocaleString("id-ID") +
      ` <i class="bi bi-basket3-fill"></i>`;
  } else {
    document.getElementById("bar-checkout").classList.add("d-none");

    localStorage.setItem("cart", JSON.stringify(addProduct));
  }
};

btnAddCard.addEventListener("click", () => {
  addProduct.push(selectedData);

  localStorage.setItem("cart", JSON.stringify(addProduct));

  updateCartDisplay();
});
