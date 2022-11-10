if (config) {
    var config_values = JSON.parse(config.value);
    console.log(config_values);
    if (config_values.save_card == 1) {
        const settings = new Models.Settings();
        settings.setupEndpoint(config_values.end_point);
        settings.setupCredentials(config_values.key, config_values.hash);
    }
    var userToken = document.getElementById("user")?.value;
}

if (userToken.length) {
    userToken = JSON.parse(userToken);
    createUser(userToken, userToken.pixel_token);
}

function errorMessage(text) {
    $.oc.flashMsg({
        text: text,
        class: "error",
        interval: 3,
    });
}

function logger(message, data) {
    message = `[pixelpay-easyshop-sdk] ${message}`;

    if (data) {
      console.log(message, data);
    } else {
      console.log(message);
    }
}

function setShippingsAddresses(cart, formData) {

  cart = JSON.parse(cart);
  cart.shipping_address.city = formData["shipping_address[city]"];
  cart.shipping_address.state = formData["shipping_address[state]"];
  cart.shipping_address.first_line = formData["shipping_address[first_line]"];
  cart.shipping_address.second_line = formData["shipping_address[second_line]"];
  cart.shipping_address.zip = formData["shipping_address[zip]"];

  cart.billing_address.city = formData["billing_address[city]"];
  cart.billing_address.state = formData["billing_address[state]"];
  cart.billing_address.first_line = formData["billing_address[first_line]"];
  cart.billing_address.second_line = formData["billing_address[second_line]"];
  cart.billing_address.zip = formData["billing_address[zip]"];

  return cart;
}

// function createUser(activeUser, customerToken) {
//   if (customerToken == null) {
//     PixelPay.tokenize()
//       .createCustomer(activeUser.email)
//       .then(function (response) {
//         token_user = response.data.token;
//         saveTokenToUser(token_user, activeUser.id);
//       })
//       .catch(function (err) {
//         reject(err);
//         errorMessage(err);
//       });
//   }
// }
function tokenCardToUser(card, billing) {
    const settings = new Models.Settings();
    settings.setupEndpoint(config_values.end_point);
    settings.setupCredentials(config_values.key, config_values.hash);

    const tokenization = new Service.Tokenization(settings);

    return tokenization.vaultCard(card_token).then((response) => {
        if (Entities.CardResult.validateResponse(response)) {
          const result = Entities.CardResult.fromResponse(response)
          console.log(result);
          saveCardTokenInDB(response.data.token, response.data.mask)
        } else {
            errorMessage(err);
        }
    }).catch((error) => {
        errorMessage(err);
    });
}

function isSaveCardActivated(saveCard) {
    return saveCard == 1 && document.querySelector("#set_default:checked")?.value == "on" ? true : false;
}

function saveCardTokenInDB(cardToken, reference) {
  const userID = userToken.id;
  console.log("entra a save card token in db");
  axios
    .post("/saveCardTokenToUser", {
      cardToken,
      userID,
      reference,
    })
    .then(function (response) {
      $.oc.stripeLoadIndicator.hide();
    })
    .catch(function (error) {
      $.oc.stripeLoadIndicator.hide();
      errorMessage(error);
    });
}

function saveTokenToUser(token, userID) {
  axios
    .post("/saveTokenToUser", {
      token,
      userID,
    })
    .then(function (response) {
      $.oc.stripeLoadIndicator.hide();
    })
    .catch(function (error) {
      $.oc.stripeLoadIndicator.hide();
      errorMessage(error);
    });
}

function deleteCardTokenFromDB(userID, reference) {
  axios
  .post("/deleteCardToken", {
    userID,
    reference,
  })
  .then(function (response) {
    $.oc.stripeLoadIndicator.hide();
  })
  .catch(function (error) {
    $.oc.stripeLoadIndicator.hide();
    errorMessage(error);
    return false;
  });
  return;
}

function getCardTokenFromDB(userID, reference) {
  axios
  .post("/getCardToken", {
    userID,
    reference,
  })
  .then(function (response) {
    $.oc.stripeLoadIndicator.hide();
  })
  .catch(function (error) {
    $.oc.stripeLoadIndicator.hide();
    errorMessage(error);
    return false;
  });
  return;
}

function openOrderResume(id, hash) {
  window.open(
    window.location.origin +
      "/cart?order_id=" +
      id +
      "&thanks=true&paymentHash=" +
      hash,
    "_self"
  );
}

function paymentWithToken(cart, hash) {
    settings.setupEndpoint(config_values.end_point);
    settings.setupCredentials(config_values.key, config_values.hash);

  let order = new Models.Order();
  order.id = cart.id;
  order.currency = cart.currency;
  order.customer_name = cart.customer_first_name + " " + cart.customer_last_name;
  order.customer_email = cart.customer_email;
  order.amount = parseFloat(cart.total).toFixed(2);

  let sale = new Requests.SaleTransaction();

  if(config_values.is3ds) {
    sale.withAuthenticationRequest();
  }

  sale.setOrder(order);
  sale.setCardToken(getCardTokenToPay());
  let service = new Services.Transaction(settings);

  service.doSale(sale).then((response) => {
    if (response.success) {
        logger(response);
        $.oc.stripeLoadIndicator.hide();
        openOrderResume(cart.id, hash);
    } else {
        //alert(response.message);
        throw response.message;
    }
  }).catch((error) => {
    alert(error);
    $.oc.stripeLoadIndicator.hide();
    errorMessage(error);
    return false;
  });
}

function paymentWithSDK(cart, cardData, hash) {
    const settings = new Models.Settings();
    settings.setupEndpoint(config_values.end_point);
    settings.setupCredentials(config_values.key, config_values.hash);

    try {
        let card = new Models.Card();
        card.number = cardData.cc_number.split(" ").join("");
        card.cvv2 = cardData.cc_cvv;
        card.expire_month = cardData.cc_exp[0] + cardData.cc_exp[1];
        card.expire_year = cardData.cc_exp[5] + cardData.cc_exp[6];
        card.cardholder = cardData.cc_name;

        let billing = new Models.Billing()
        billing.address = cart.billing_address.first_line;
        billing.country = cart.billing_address.country;
        billing.state = cart.billing_address.state;
        billing.city = cart.billing_address.city;
        billing.phone = cart.customer_phone;
        if (cart.billing_address.zip) {
            billing.zip = cart.billing_address.zip;
        }

        let order = new Models.Order();
        order.id = cart.id;
        order.currency = cart.currency;
        order.customer_name = cart.customer_first_name + " " + cart.customer_last_name;
        order.customer_email = cart.customer_email;
        order.amount = parseFloat(cart.total).toFixed(2);

        let sale = new Requests.SaleTransaction();

        if(config_values.is3ds) {
            sale.withAuthenticationRequest();
        }

        sale.setOrder(order);
        sale.order_amount = order.amount;
        sale.setCard(card);
        sale.setBilling(billing);

        let service = new Services.Transaction(settings);
        console.log(sale);
        service.doSale(sale).then((response) => {
            if (response.success) {
                console.log(response);
                $.oc.stripeLoadIndicator.hide();
                logger(response);
                openOrderResume(cart.id, hash);
                if (isSaveCardActivated(config_values.save_card) && customerToken !== null) {
                    console.log("quiere save tarjeta");
                //card.setCustomerToken(customerToken);
                tokenCardToUser(card, billing);

                //if (isSaveCardActivated(config.save_card) && customerToken !== null) {
                    //         card.setCustomerToken(customerToken);
                    //         tokenCardToUser(card, billing);
                    //       }
                }
            } else {
                console.log(response);
                throw response.message;
            }
        }).catch((error) => {
            console.error("Error: ", error);
            $.oc.stripeLoadIndicator.hide();
            alert(error);
            return false;
        });
    } catch(e) {
        console.log(e);
    }
}

function isPaymentByToken() {
  var radios = document.getElementsByName("gateway");
  let gateway = [];
  for (var i = 0, length = radios.length; i < length; i++) {
    if (radios[i].checked) {
      gateway = radios[i].value;
      break;
    }
  }
  return gateway.length > 15 ? true : false;
}

function getCardTokenToPay() {
  var radios = document.getElementsByName("gateway");
  let gateway = [];
  for (var i = 0, length = radios.length; i < length; i++) {
    if (radios[i].checked) {
      gateway = radios[i].value;
      break;
    }
  }
  return gateway;
}

function createrOrder() {
  $.oc.stripeLoadIndicator.show();
  const form = document.getElementById("form");
  let cart = document.getElementById("cartvalue").value;
  let user =
    document.getElementById("user").value == ""
      ? null
      : document.getElementById("user").value;

  let formData = Object.values(form).reduce((obj, field) => {
    obj[field.name] = field.value;
    return obj;
  }, {});


  cart = setShippingsAddresses(cart, formData);
  cart = JSON.stringify(cart);
  formData = JSON.stringify(formData);
  axios
    .post("/createOrder", {
      formData,
      cart,
      user,
    })
    .then(function (response) {
      if (response.data.success) {
        if (isPaymentByToken()) {
          paymentWithToken(
            response.data.data,
            response.data.payment_hash,
          );
        return true;
        } else {
          paymentWithSDK(
            response.data.data,
            JSON.parse(formData),
            response.data.payment_hash,
          );
        return true;
        }
      } else {
        $.oc.stripeLoadIndicator.hide();
        errorMessage(response.data.message);
        return false;
      }
    })
    .catch(function (error) {
      $.oc.stripeLoadIndicator.hide();
      errorMessage(error);
      return false;
    });
}


function hiddenDataHolder() {
	var element = document.getElementById('pixel_card_holder');
	element.style.display = 'none';
}

function showDataHolder() {
	var element = document.getElementById('pixel_card_holder');
	element.style.display = 'block';
}

