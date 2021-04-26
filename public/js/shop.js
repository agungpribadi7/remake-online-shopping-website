"use strict";
function init() {
	$("#nav-menu").sidenav();
	$(".collapsible").collapsible();
	$("select").formSelect();
	$(".tabs").tabs();
	$('.tooltipped').tooltip();

	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		i18n: {
			cancel: 'Batal',
			clear: 'Bersihkan',
			months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
			weekdays: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
			weekdaysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
			weekdaysAbbrev: ['M', 'S', 'S', 'R', 'K', 'J', 'S']
		}
	});
	$(".user-dropdown").dropdown({
		coverTrigger: true,
		constrainWidth: false,
		alignment: "right",
	});
	refreshCartBadge();
}
function ajaxError(xhr, status, ret) {
	M.toast({html: 'Uh oh! Terdapat kesalahan! :('});
	console.debug(xhr);
}
$(document).ajaxError(ajaxError);
function createModalElem(msg) {
    let modal = $("<div class='modal'><div class='modal-content'></div><div class='modal-footer'></div></div>")
    modal.find(".modal-content").html(msg);
    return modal;
}
function confirm2(msg, yesTxt = "Ya", noTxt = "Tidak", yesCallback, noCallback) {
	var confirmElem = createModalElem(msg);
    confirmElem.find(".modal-footer").append('<a href="#!" class="modal-no modal-action modal-close waves-effect btn-flat">' +  noTxt + '</a><a href="#!" class="modal-yes modal-action modal-close waves-effect waves-red btn-flat">' +  yesTxt + '</a>');
    var callbackCalled = false;
    var confirmModal = M.Modal.init(confirmElem[0], {
		onCloseEnd: function() {
			confirmElem.remove();
            confirmModal.destroy();
            if (!callbackCalled && noCallback !== undefined)
                noCallback();
		}
	});
    confirmElem.find(".modal-no").click(function() {
        callbackCalled = true;
        if (noCallback !== undefined) noCallback();
    });
    confirmElem.find(".modal-yes").click(function() {
        callbackCalled = true;
        if (yesCallback !== undefined) yesCallback();
    });
	$("body").append(confirmElem);
	confirmModal.open();
}
function alert2(msg, btnTxt = "OK", callback) {
    var alertElem = createModalElem(msg);
    alertElem.find(".modal-footer").append('<a href="#!" class="modal-action modal-close waves-effect btn-flat">' +  btnTxt + '</a>');
    var alertModal = M.Modal.init(alertElem[0], {
		onCloseEnd: function() {
			alertElem.remove();
            alertModal.destroy();
            if (callback !== undefined) callback();
		}
    });
    $("body").append(alertElem);
    alertModal.open();
}

function wishlistBtnClick(event, el, id){
	event.stopPropagation();
	butuhLogin();
	let simbol = el.innerText;
	if(simbol == "favorite_border"){
		$(el).find("i").text("favorite");
		$.post(url("Akun/addWishList"), { kirimIdBarang: id }, function() {
			M.toast({html: 'Barang berhasil dimasukkan kedalam wishlist!'});
		});
	} else {
		$(el).find("i").text("favorite_border");
		$.post(url("Akun/deleteWishList"), { kirimIdBarang: id }, function() {
			M.toast({html: 'Barang dihapus dari wishlist!'});
		}); 
	}
}

function cartBtnClick(event, id) {
	event.stopPropagation();
	let myurl = url("ShoppingCart/tambahItem");
	let jumlahBarang = $("#jumlahBarang").val();
	if (jumlahBarang == undefined)
		jumlahBarang = 1;
	if (jumlahBarang > 0)
		$.post(myurl, { kirimIdBarang: id, kirimJumlahBarang : jumlahBarang }, function() {
			M.toast({html: 'Barang berhasil dimasukkan kedalam keranjang!'});
			refreshCartBadge();
		});
	else
		alert2("Minimum jumlah barang untuk masuk keranjang adalah 1");
}

function refreshCartBadge() {
	$.get(url("ShoppingCart/GetJumlahBarang"), function(ret) {
		$("#cart-navbar").attr('data-badge', ret);
	});
}
