import "./material-design-icons.css";
import "vuetify/styles";

import { createVuetify } from "vuetify";
import { aliases, md } from "vuetify/iconsets/md";
import { VDateInput } from "vuetify/labs/VDateInput";
import { VNumberInput } from "vuetify/labs/VNumberInput";

export default createVuetify({
    components: {
        VDateInput,
        VNumberInput,
    },

    icons: {
        defaultSet: "md",
        aliases,
        sets: {
            md,
        },
    },
});
