document.addEventListener("DOMContentLoaded", function () {
    const regionSelect = document.querySelector("#region-select");
    const countrySelect = document.querySelector("#country-select");
    const countryWrapper = document.querySelector("#country-filter");
    const contactContainer = document.querySelector(".contact_persons");
    const posts = document.querySelectorAll(".contact-person");

    contactContainer.style.display = "none";
    countryWrapper.style.display = "none";

    let selectedRegionID = null;
    let selectedCountryID = null;
    let regionAndChildrenIDs = [];

    regionSelect.addEventListener("change", function () {
        selectedRegionID = this.value ? parseInt(this.value) : null;
        selectedCountryID = null;
        regionAndChildrenIDs = [];

        countrySelect.innerHTML = "";
        countryWrapper.style.display = "none";
        contactContainer.style.display = "none";

        if (!selectedRegionID) {
            filterContacts();
            return;
        }

        fetch(`${window.location.origin}/wp-json/wp/v2/countries?parent=${selectedRegionID}`)
        .then(response => response.json())
        .then(countries => {
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

                    regionAndChildrenIDs.push(country.id);
                });

                countryWrapper.style.display = "flex";
            }

            regionAndChildrenIDs.push(selectedRegionID);

            filterContacts();
        });
    });

    countrySelect.addEventListener("change", function () {
        selectedCountryID = this.value ? parseInt(this.value) : null;
        filterContacts();
    });

    function filterContacts() {
        let visibleCount = 0;

        posts.forEach(post => {
            const classes = post.className;
            let show = false;

            if (selectedRegionID && !selectedCountryID) {
                show = classes.includes(`cat-${selectedRegionID}`);
            } else if (selectedRegionID && selectedCountryID) {
                show = classes.includes(`cat-${selectedRegionID}`) || classes.includes(`cat-${selectedCountryID}`);
            } else {
                show = true;
            }

            if (show) {
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
