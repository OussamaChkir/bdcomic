document.addEventListener("DOMContentLoaded", function () {
    const regionSelect = document.querySelector("#region-select");
    const countrySelect = document.querySelector("#country-select");
    const countryWrapper = document.querySelector("#country-filter");
    const contactContainer = document.querySelector(".contact_persons");
    const posts = document.querySelectorAll(".contact-person");

    contactContainer.style.display = "none";
    countryWrapper.style.display = "none";

    regionSelect.addEventListener("change", function () {
        const regionID = this.value;

        countrySelect.innerHTML = `<option value=""><?php _e('Choose country', 'korsch'); ?></option>`;
        countryWrapper.style.display = "none";
        contactContainer.style.display = "none";

        if (!regionID) return;

        fetch(`${window.location.origin}/wp-json/wp/v2/countries?parent=${regionID}`)
        .then(response => response.json())
        .then(countries => {
            countrySelect.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Choose country";
            defaultOption.selected = true;
            countrySelect.appendChild(defaultOption);

            if (countries.length > 0) {
                countries.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.id;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });

                countryWrapper.style.display = "flex";
            }

            filterContacts(regionID);
        });
    });

    countrySelect.addEventListener("change", function () {
        const regionID = regionSelect.value;
        const countryID = this.value;

        if (countryID) {
            filterContacts(null, countryID);
        } else {
            filterContacts(regionID);
        }
    });

    function filterContacts(regionID = null, countryID = null) {
        let visibleCount = 0;

        posts.forEach(post => {
            const classes = post.className;
            const matchRegion = regionID ? classes.includes(`cat-${regionID}`) : true;
            const matchCountry = countryID ? classes.includes(`cat-${countryID}`) : true;

            if (matchRegion && matchCountry) {
                post.style.display = "flex";
                visibleCount++;
            } else {
                post.style.display = "none";
            }
        });

        const messageBox = document.querySelector("#contact-results-message");
        const countSpan = document.querySelector("#results-count");

        if (visibleCount > 0) {
            countSpan.textContent = visibleCount;
            messageBox.style.display = "block";
            contactContainer.style.display = "flex";
        } else {
            messageBox.style.display = "none";
            contactContainer.style.display = "none";
        }
    }
});
