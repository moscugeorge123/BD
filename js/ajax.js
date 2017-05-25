products = {};
products.getProduct = function (elem) {
    var product_id = elem.dataset.prodId;
    $.ajax({
        method: 'POST',
        url: '../products/ajax.php',
        data: {
            action: 'get_product',
            product_id: product_id
        },
        success: function (data) {
            var product = JSON.parse(data);
            var element = "<div class='col-md-4'>" +
                "<div class='thumbnail'>" +
                "<img src='"+product.IMAGESOURCE+"' alt='img' class='img-rounded img-responsive'>" +
                "</div>" +
                "</div>" +
                "<div class='col-md-8'>" +
                "<div class='thumbnail'>" +
                "<h2>"+product.NAME+"</h2>" +
                "<p>Description: "+product.DESCRIPTION+"</p>" +
                "<p>Price: "+product.PRICE+"</p>" +
                "<p>Stock: "+product.STOCK+"</p>" +
                "<button class='btn btn-danger delete pull-right' data-prod-id='"+product.ID+"'>DELETE</button>" +
                "<button class='btn btn-success add pull-right' data-prod-id='"+product.ID+"'>ADD TO CART</button>" +
                "</div>" +
                "</div>";
            $('.wrapper').append(element);
        }
    });
};

products.getProducts = function (search = '') {
    $.ajax({
        method: 'POST',
        url: '../products/ajax.php',
        data: {
            action: 'get_products',
            limit: 9,
            page: 3,
            search: search
        },
        success: function (data) {
            var products = JSON.parse(JSON.parse(data).products);
            console.log(products.length);
            for(i=0; i<products.length; i++) {
                var product = products[i];
                var element =
                    "<div class='col-md-4'>" +
                    "<div class='thumbnail'>"+
                    "<img src='"+product.IMAGESOURCE+"' alt='image'>"+
                    "<div class='caption'>" +
                    "<h2>"+product.NAME+"</h2>" +
                    "<div class='btn btn-success view' data-prod-id='" + product.ID + "' onclick='getProd(this)'>View</div>"+
                    "<div class='btn btn-success add' data-prod-id='" + product.ID + "' onclick='addToCart(this)'>Add to cart</div>"+
                    "<div class='btn btn-danger delete' data-prod-id='" + product.ID + "' onclick='deleteProd(this)'>Delete</div>"+
                    "</div>" +
                    "</div>" +
                    "</div>";
                $('.wrapper').append(element);
            }
        }
    });
};

function getProd(elem) {
    $('.wrapper').remove();
    $('.row').append("<div class='wrapper'></div>");
    products.getProduct(elem);
}

function getProds(search = '') {
    $('.wrapper').fadeOut().remove();
    $('.row').append("<div class='wrapper'></div>");
    products.getProducts(search);
}

$(document).keyup(function (key) {
    if(key.keyCode == 13)
        getProds($('#search').val());
});

$(document).on('click', '#login',function () {
    var username = $('#username').val();
    var password = $('#password').val();
    console.log("HELO");
    $.ajax({
        method: 'POST',
        url: '../users/ajax.php',
        data: {
            username: username,
            password: password,
            action: 'login'
        },
        success: function (data) {
            console.log(data);
        }
    });
});

$(document).on('click', '#register', function () {
    var username = $('#username').val();
    var password = $('#password').val();
    var email = $('#email').val();
    var gender = $('#gender').val();
    var birthday = $('#birthday').val();
    var phone = $('#phone').val();
    var addres = $('#addres').val();
    var fullName = $('#full-name').val();


    $.ajax({
        method: 'POST',
        url: '../users/ajax.php',
        data: {
            username: username,
            password: password,
            email: email,
            gender: gender,
            birthday: birthday,
            phone: phone,
            addres: addres,
            'full-name': fullName,
            action: 'register'
        },
        success: function (data) {
            console.log(data);
        }
    });
});

function addToCart(elem) {
    var product_id = elem.dataset.prodId;
    console.log(product_id);
    $.ajax({
        method: 'POST',
        url: '../products/ajax.php',
        data: {
           product_id: product_id,
           action: 'add_to_cart'
        },
        success: function (data) {
            console.log(data);
        }
    });
}

function deleteProd(elem) {
    var product_id = elem.dataset.prodId;
    $.ajax({
        method: 'POST',
        url: '../products/ajax.php',
        data: {
            product_id: product_id,
            action: 'delete'
        },
        success: function (data) {
            $(elem).parent().parent().parent().slideUp();
            console.log(data);
        }
    });
}



