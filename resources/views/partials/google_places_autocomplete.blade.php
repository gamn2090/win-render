<script>
  window.initWinPlacesAutocomplete = async function () {
    if (!window.google || !window.google.maps) return;
    const { Autocomplete } = await google.maps.importLibrary("places");

    document.querySelectorAll('[data-places-autocomplete]').forEach(function (input) {
      if (input.dataset.placesInitialized) return;
      input.dataset.placesInitialized = '1';

      var types = input.dataset.placesTypes ? input.dataset.placesTypes.split(',') : ['(cities)'];
      var autocomplete = new Autocomplete(input, {
        types: types,
        fields: ['address_components', 'formatted_address'],
      });

      autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        var components = place.address_components || [];

        function componentValue(type, useShort) {
          var match = components.find(function (comp) { return comp.types.includes(type); });
          if (!match) return '';
          return useShort ? match.short_name : match.long_name;
        }

        var city = componentValue('locality') || componentValue('postal_town') || componentValue('sublocality') || '';
        var state = componentValue('administrative_area_level_1', true) || '';

        if (input.dataset.placesSelf === 'city' && city) {
          input.value = city;
        }

        var stateTargetId = input.dataset.placesFillState;
        if (stateTargetId) {
          var stateTarget = document.getElementById(stateTargetId);
          if (stateTarget) stateTarget.value = state;
        }

        var cityTargetId = input.dataset.placesFillCity;
        if (cityTargetId) {
          var cityTarget = document.getElementById(cityTargetId);
          if (cityTarget) cityTarget.value = city;
        }
      });
    });
  };
</script>
