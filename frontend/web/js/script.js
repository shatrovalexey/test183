const { createApp, ref, onMounted } = Vue;
const cssSelectorTrans = "#translators";

createApp({
    setup() {
        const translatorsObj = document.querySelector(cssSelectorTrans);
        const [translators, error, loading, fetchData,] = [
            ... [[], null, true,].map(value => ref(value))
            , url => fetch(url).then(data => data.json())
            ,
        ];
        const fetchTranslators = () => {
            loading.value = true;

            fetchData(translatorsObj.dataset.src)
                .then(data => {
					translators.value = data.map(translator => ({... translator, profile: null, loading: false, error: null, loaded: false}));
				})
                .catch(exception => error.value = exception)
                .finally(() => loading.value = false);
        };
        const fetchProfile = (translator, {target: { open, dataset: { src } },}) => {
            if (!open || translator.loaded) return;

            translator.loading = true;

            fetchData(src)
                .then(data => [translator.profile, translator.loaded,] = [
                    data.map(translation => ({...translation, schedule: null, scheduleLoading: false, scheduleLoaded: false})), true,
                ])
                .catch(exception => translator.error = exception)
                .finally(() => translator.loading = false);
        };
        const fetchSchedule = (translation, {target: { open, dataset: { src } },}) => {
            if (!open || translation.scheduleLoaded) return;

            translation.scheduleLoading = true;

            fetchData(src)
                .then(data => [translation.schedule, translation.scheduleLoaded,] = [data, true,])
                .catch(exception => console.error(exception))
                .finally(() => translation.scheduleLoading = false);
        };
        
        onMounted(fetchTranslators);
        
        return {translators, error, loading, fetchProfile, fetchSchedule,};
    }
}).mount(cssSelectorTrans);