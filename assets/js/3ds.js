var config = JSON.parse(document.getElementById("config").value);
console.log(document.getElementById("config").value);
PixelPay.setup(config.key, config.hash, config.end_point);


var userToken = document.getElementById("user")?.value;

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

function setShippingsAddresses(cart, formData) {

  cart = JSON.parse(cart);
  cart.shipping_address.city = formData["shipping_address[city]"];
  cart.shipping_address.first_line = formData["shipping_address[first_line]"];
  cart.shipping_address.second_line = formData["shipping_address[second_line]"];
  cart.shipping_address.zip = formData["shipping_address[zip]"];

  cart.billing_address.city = formData["billing_address[city]"];
  cart.billing_address.first_line = formData["billing_address[first_line]"];
  cart.billing_address.second_line = formData["billing_address[second_line]"];
  cart.billing_address.zip = formData["billing_address[zip]"];

  console.log('setShipping cart:' , cart);
  return cart;
}

function createUser(activeUser, customerToken) {
  console.log("create user ", customerToken);
  if (customerToken == null) {
    console.log("entro al if");
    PixelPay.tokenize()
      .createCustomer(activeUser.email)
      .then(function (response) {
        console.log("response del create user 2 ");
        token_user = response.data.token;
        saveTokenToUser(token_user, activeUser.id);
      })
      .catch(function (err) {
        console.error("Error: ", err);
        reject(err);
        errorMessage(err);
      });
  }
}

function tokenCardToUser(card, billing) {
  return new Promise((resolve, reject) => {
    PixelPay.tokenize()
      .createCard(card, billing)
      .then(function (response) {})
      .catch(function (err) {
        console.error("Error: ", err);
        errorMessage(err);
      });
  });
}

function isSaveCardActivated(saveCard) {
    return saveCard == 1 && document.querySelector("#set_default:checked")?.value == "on" ? true : false;
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
	console.log('Hash config: ', hash);
  let order = PixelPay.newOrder();

  order.setOrderID(cart.id);
  order.setAmount(parseFloat(cart.total).toFixed(2));
  order.setFullName(cart.customer_first_name + " " + cart.customer_last_name);
  order.setEmail(cart.customer_email);
  order.setCategory('october');

  let card = PixelPay.newCard();
  card.setToken(getCardTokenToPay());
  order.addCard(card);
  console.log(order);
  //return;
  PixelPay.payOrder(order)
    .then(function (response) {
      console.log(response);
      $.oc.stripeLoadIndicator.hide();
      openOrderResume(cart.id, hash);
    })
    .catch(function (err) {
      console.error("Error: ", err);
      $.oc.stripeLoadIndicator.hide();
      errorMessage(err.message);
    });
}

function paymentWith3DS(cart, cardData, hash) {
try {
  let customerToken = userToken?.pixel_token;
  console.log('1');
  console.log('configuracion: ', config);
  let order = PixelPay.newOrder();

  order.setOrderID(cart.id);
  order.setAmount(parseFloat(cart.total).toFixed(2));
  order.setFullName(cart.customer_first_name + " " + cart.customer_last_name);
  order.setEmail(cart.customer_email);
  order.setCategory('october');
  console.log('2');
  let card = PixelPay.newCard();

  console.log('3');
  card.setCardNumber(cardData.cc_number.split(" ").join(""));
  card.setCvv(cardData.cc_cvv);
  card.setCardHolder(cardData.cc_name);
  card.setExpirationDate(
    cardData.cc_exp[0] +
      cardData.cc_exp[1] +
      "-" +
      cardData.cc_exp[5] +
      cardData.cc_exp[6]
  );
  console.log('4');

  order.addCard(card);
  console.log('5');
  let billing = PixelPay.newBilling();

  console.log('cart', cart);
  billing.setCity(cart.billing_address.city);
  billing.setState(cart.billing_address.state);
  billing.setCountry(cart.billing_address.country);

  billing.setAddress(cart.billing_address.first_line);
  billing.setPhoneNumber(cart.customer_phone);
  order.addBilling(billing);
  console.log('7');

  console.log(
    "¿ Tokenizar tarjeta ?",
    isSaveCardActivated(config.save_card) && customerToken !== null
  );
  console.log("customerToken", customerToken);
  if (isSaveCardActivated(config.save_card) && customerToken !== null) {
    console.log("TARJETA TOKENIZADA a ", customerToken);
    card.setCustomerToken(customerToken);
    tokenCardToUser(card, billing);
  }
  console.log('8');
  	PixelPay.payOrder(order)
		.then(function (response) {
		$.oc.stripeLoadIndicator.hide();
		console.log(response);
		openOrderResume(cart.id, hash);
		})
		.catch(function (err) {
			console.log('error pay order', err);
		$.oc.stripeLoadIndicator.hide();
		console.error("Error: ", err);
		errorMessage(err.message);
		});

		console.log('9');
	}catch(err) {
		$.oc.stripeLoadIndicator.hide();
		errorMessage(err.message);
		console.log(err);
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

  var datosForm = Object.values(form).reduce((obj, field) => {
    obj[field.name] = field.value;
    return obj;
  }, {}); 

  let formData = Object.values(form).reduce((obj, field) => {
    obj[field.name] = field.value;
    return obj;
  }, {});

  console.log('datos formulario; ', formData);

  cart = setShippingsAddresses(cart, formData);
  console.log('cart shipping: ', cart);
  cart = JSON.stringify(cart);
  console.log('before cart: ', cart);
  formData = JSON.stringify(formData);
  axios
    .post("/createOrder", {
      formData,
      cart,
      user,
    })
    .then(function (response) {
		console.log('respoonse data config' ,response);
		//debugger;
      if (response.data.success) {
        if (isPaymentByToken()) {
			console.log('response config: ', response);
          paymentWithToken(
            response.data.data,
            response.data.payment_hash,
          );
        } else {
          paymentWith3DS(
            response.data.data,
            JSON.parse(formData),
            response.data.payment_hash,
          );
        }
      } else {
        $.oc.stripeLoadIndicator.hide();
        errorMessage(response.data.message);
      }
    })
    .catch(function (error) {
      $.oc.stripeLoadIndicator.hide();
      errorMessage(error);
    });
}


function hiddenDataHolder() {
	var element = document.getElementById('pixel_card_holder');
	element.style.display = 'none';
}

function showDataHolder(){
	var element = document.getElementById('pixel_card_holder');
	element.style.display = 'block';
}