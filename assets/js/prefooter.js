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

            let childIDs = [];

            if (countries.length > 0) {
                countries.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.id;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);

                    childIDs.push(country.id);
                });

                countryWrapper.style.display = "flex";
            }

            // Add the region ID itself, in case posts are assigned to the parent too
            childIDs.push(parseInt(regionID));

            filterContactsByMultiple(childIDs);
        });
    });

    countrySelect.addEventListener("change", function () {
        const countryID = this.value;

        if (countryID) {
            filterContactsByMultiple([parseInt(countryID)]);
        } else {
            const regionID = regionSelect.value;
            if (!regionID) return;

            // Re-fetch countries for region
            fetch(`${window.location.origin}/wp-json/wp/v2/countries?parent=${regionID}`)
            .then(response => response.json())
            .then(countries => {
                let termIDs = countries.map(c => c.id);
                termIDs.push(parseInt(regionID));
                filterContactsByMultiple(termIDs);
            });
        }
    });

    function filterContactsByMultiple(termIDs = []) {
        let visibleCount = 0;

        posts.forEach(post => {
            const classes = post.className;
            const matches = termIDs.some(id => classes.includes(`cat-${id}`)) || classes.includes("cat-all");

            if (matches) {
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
