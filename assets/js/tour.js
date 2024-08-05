function startTour() {
  const tour = new Shepherd.Tour({
    useModalOverlay: true,
    defaultStepOptions: {
      cancelIcon: {
        enabled: true,
      },
      classes: "shepherd-theme-arrows",
      scrollTo: { behavior: "smooth", block: "center" },
    },
  });

  const steps = {
    dashboard: [
      {
        id: "step1",
        title :"HOME PAGE",
        text: "Welcome to the home page!",
        attachTo: { element: ".card", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step2",
        title :"NAVIGATION BAR",
        text: "Here is the navbar from where you can navigate through different sections!",
        attachTo: { element: ".navbar", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step3",
        title :"YOUR NAME",
        text: "Here you see your lovely name.",
        attachTo: { element: ".card-header", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step4",
        title :"PERFORMANCE TAB",
        text: "Here you can get a quick overview of your business performance.",
        attachTo: { element: ".card-body", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step5",
        title :"DATE SELECTOR",
        text: "Here you can change the date range to see your performance over time.",
        attachTo: { element: "#dateRangeSelect", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step6",
        title :"TOTAL SALES",
        text: "This section shows the Total Sales. You can see how much revenue your shop has generated over a specific period.",
        attachTo: { element: ".alert-success", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step7",
        title :"TOTAL PURCHASES",
        text: "This is the Total Purchases section. It gives you an overview of all the purchases made for restocking your shop.",
        attachTo: { element: ".alert-info", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step8",
        title :"TOTAL UDHAAR",
        text: "Here you can see the Total Udhaar. This includes all sales made on credit (udhaar).",
        attachTo: { element: ".alert-warning", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step9",
        title :"TOTAL PROFIT",
        text: "This section displays the Total Profit, representing your shop's earnings after deducting the cost of goods sold.",
        attachTo: { element: ".alert-profit", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step10",
        title :"TOTAL EXPENSES",
        text: "Here you can see the Total Expenses. This includes all operational costs such as rent, utilities, and other expenses.",
        attachTo: { element: ".alert-danger", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "step11",
        title :"TOTAL PROFIT AFTER EXPENSES",
        text: "This section shows the Profit After Expenses, highlighting your net profit after accounting for all operating costs.",
        attachTo: { element: ".alert-total_profit", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: function () {
              localStorage.setItem("tourStep", "inventory-step1");
              window.location.href = "index.php?page=inventory";
            },
          },
        ],
      },
    ],
    inventory: [
      {
        id: "inventory-step1",
        title :"INVENTORY",
        text: "View all your items here, complete with pictures and current stock status. You can see whether each item is in stock, out of stock, or available for purchase.",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "inventory-step2",
        title :"SEARCH BAR",
        text: "Here you can search anything to get your inventory item.",
        attachTo: { element: ".dataTables_filter", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "inventory-step3",
        title :"PREVIEW IMAGE",
        text: "Here you can click on an image to preview it in big.",
        attachTo: { element: ".preview", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: function () {
              localStorage.setItem("tourStep", "sales-step1");
              window.location.href = "index.php?page=sales";
            },
          },
        ],
      },
    ],
    sales: [
      {
        id: "sales-step1",
        title :"NEW SALES",
        text: "Click here to create a new sale. This button will direct you to a form where you can input sale details.!",
        attachTo: { element: "#new_sales", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "sales-step2",
        title :"SALES OVERVIEW",
        text: "Here you will see a overview of all sales made, organized by date. You can view details of each sale by clicking on the respective entry",
        attachTo: { element: ".card", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "sales-step3",
        title :"ACTION BAR",
        text: "To update a sales's details, click the 'Edit/Delete' button next to the sales you want to modify.You can make changes and save them",
        attachTo: { element: ".sales-btn", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "receiving-step1");
              window.location.href = "index.php?page=receiving";
            },
          },
        ],
      },
    ],
    receiving: [
      {
        id: "receiving-step1",
        title :"NEW PURCHASE",
        text: "Click here to create a new Purchase. This button will direct you to a form where you can input purchase details.!",
        attachTo: { element: "#new_receiving", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "receiving-step2",
        title :"PURCHASES OVERVIEW",
        text: "Here you will see a overview of all purchases made, organized by date. You can view details of each purchase by clicking on the respective entry",
        attachTo: { element: ".card", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "receiving-step3",
        title :"ACTION BAR",
        text: "To update a purchases's details, click the 'Edit/Delete' button next to the purchases you want to modify.You can make changes and save them",
        attachTo: { element: ".receiving-btn", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "product-step1");
              window.location.href = "index.php?page=product";
            },
          },
        ],
      },
    ],
    product: [
      {
        id: "product-step1",
        title :"PRODUCT FORM",
        text: "Here you can add a new product. This form where you can enter details about the new product!",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "product-step2",
        title :"PRODUCT OVERVIEW",
        text: "Here, you'll see a overview of all your products. Each entry shows the product's name, price, and stock level.",
        attachTo: { element: ".table", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "product-step3",
        title :"ACTION BAR",
        text: "To update a product's details, click the 'Edit/Delete' button next to the product you want to modify.You can make changes and save them",
        attachTo: { element: ".product-btn", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "supplier-step1");
              window.location.href = "index.php?page=supplier";
            },
          },
        ],
      },
    ],
    supplier: [
      {
        id: "supplier-step1",
        title :"SUPPLIER FORM",
        text: "Here you create a new supplier. This is form where you can input supplier details.!",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "supplier-step2",
        title :"SUPPLIER OVERVIEW",
        text: "Here you will see a overview of all supplier made, organized by date. You can view details of each supplier by clicking on the respective entry",
        attachTo: { element: ".table", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "supplier-step3",
        title :"ACTION BAR",
        text: "To update a supplier's details, click the 'Edit/Delete' button next to the supplier you want to modify.You can make changes and save them",
        attachTo: { element: ".supplier-btn", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "customer-step1");
              window.location.href = "index.php?page=customer";
            },
          },
        ],
      },
    ],
    customer: [
      {
        id: "customer-step1",
        title :"CUSTOMER FORM",
        text: "Here you create a new customer. This is form where you can input customer details.!",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "customer-step2",
        title :"CUSTOMER OVERVIEW",
        text: "Here you will see a overview of all customer made, organized by date. You can view details of each customer by clicking on the respective entry",
        attachTo: { element: ".table", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "customer-step3",
        title :"ACTION BAR",
        text: "To update a customer's details, click the 'Edit/Delete' button next to the customer you want to modify.You can make changes and save them",
        attachTo: { element: ".customer-btn", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "credit-step1");
              window.location.href = "index.php?page=credit";
            },
          },
        ],
      },
    ],
    credit: [
      {
        id: "credit-step1",
        title :"UDHAAR OVERVIEW",
        text: "Here you will see a overview of all credit made, organized by date. You can view details of each credit by clicking on the respective entry",
        attachTo: { element: ".card", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "credit-step2",
        title :"EDIT BUTTON",
        text: "To update a credit's details, click the 'Edit' button next to the credit you want to modify.You can make changes and save them",
        attachTo: { element: ".btn-sm", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "expenses-step1");
              window.location.href = "index.php?page=expenses";
            },
          },
        ],
      },
    ],
    expenses: [
      {
        id: "expenses-step1",
        title :"EXPENSE FORM",
        text: "Here you create a new expenses. This is form where you can input expenses details.!",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "expenses-step2",
        title :"EXPENSE OVERVIEW",
        text: "Here you will see a overview of all expenses made, organized by date. You can view details of each expenses by clicking on the respective entry",
        attachTo: { element: ".table", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "expenses-step3",
        title :"ACTION BAR",
        text: "To update a expenses's details, click the 'Edit/Delete' button next to the expenses you want to modify.You can make changes and save them",
        attachTo: { element: ".expense-btn", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "users-step1");
              window.location.href = "index.php?page=users";
            },
          },
        ],
      },
    ],
    users: [
      {
        id: "users-step1",
        title :"NEW USER",
        text: "Click here to create a new user. This button will direct you to a form where you can input user details.!",
        attachTo: { element: ".btn-outline-primary", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "users-step2",
        title :"USERS OVERVIEW",
        text: "Here you will see a overview of all users made, organized by date. You can view details of each user by clicking on the respective entry",
        attachTo: { element: ".card-body", on: "bottom" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "users-step3",
        title :"ACTION BAR",
        text: "To update a users's details, click the 'Edit/Delete' button next to the users you want to modify.You can make changes and save them",
        attachTo: { element: ".btn-group", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.setItem("tourStep", "account-step1");
              window.location.href = "index.php?page=account";
            },
          },
        ],
      },
    ],
    account: [
      {
        id: "account-step1",
        title :"ACCOUNT OVERVIEW",
        text: "Here you will see a overview of your account. You can view details  by clicking on the respective entry",
        attachTo: { element: ".card", on: "bottom" },
        buttons: [
          {
            text: "Next",
            action: tour.next,
          },
        ],
      },
      {
        id: "account-step2",
        title :"EDIT BUTTON",
        text: "To update a account's details, click the 'Edit' button next to the account you want to modify.You can make changes and save them",
        attachTo: { element: ".edit_product", on: "top" },
        buttons: [
          {
            text: "Previous",
            action: tour.back,
          },
          {
            text: "Finish",
            action: function () {
              localStorage.removeItem("tourStep");
              tour.complete();
            },
          },
        ],
      },
    ],
  };

  const currentPage = getPageFromUrl();
  const currentStep =
    localStorage.getItem("tourStep") || steps[currentPage][0].id;

  if (steps[currentPage]) {
    const stepIndex = steps[currentPage].findIndex(
      (step) => step.id === currentStep
    );
    steps[currentPage].slice(stepIndex).forEach((step) => tour.addStep(step));

    // Save the current step index on each step change
    tour.on("show", function () {
      const currentStep = tour.getCurrentStep();
      if (currentStep) {
        localStorage.setItem("tourStep", currentStep.id);
      }
    });

    // Clear the tour step when the tour is completed or canceled
    tour.on("complete", function () {
      localStorage.removeItem("tourStep");
      localStorage.setItem("tourShown", "true");
    });

    tour.on("cancel", function () {
      localStorage.removeItem("tourStep");
      localStorage.setItem("tourShown", "true");
    });

    tour.start();
  }
}

function getPageFromUrl() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get("page") || "dashboard";
}


