/* Woo Checkout Block */
if ( wp && wp.data ) {
    var unsubscribe = wp.data.subscribe(function() {
		jQuery('#mc-input').on('input', function() {
		wp.data.dispatch('wc/store/checkout').__internalSetExtensionData('wmc', {
			token: jQuery('#mc-input').val()
        })
        unsubscribe();
		});
    }, 'wc/store/cart');
}


