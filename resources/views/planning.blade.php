<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Section Calendrier -->
            <div class="card bg-base-100 shadow-xl p-6">
                <div class="flex justify-between items-center">
                    <h2 class="card-title">Calendrier</h2>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Section Liste des Événements -->
            <div class="card bg-base-100 shadow-xl p-6">
                <div class="flex justify-between items-center">
                    <h2 class="card-title">Liste des événements</h2>
                    <button class="btn btn-primary" onclick="document.getElementById('event-modal').showModal()">➕ Ajouter un événement</button>
                </div>
                <ul id="event-list" class="mt-4 space-y-3">
                    <!-- Les événements seront affichés ici dynamiquement -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal pour Ajouter un Événement -->
    <dialog id="event-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Ajouter un événement</h3>
            <form id="event-form">
                <label class="form-control w-full mt-2">
                    <span class="label-text">Nom de l'événement</span>
                    <input type="text" id="event-title" class="input input-bordered w-full" required />
                </label>
                <label class="form-control w-full mt-2">
                    <span class="label-text">Date de début</span>
                    <input type="datetime-local" id="event-start" class="input input-bordered w-full" required />
                </label>
                <label class="form-control w-full mt-2">
                    <span class="label-text">Date de fin</span>
                    <input type="datetime-local" id="event-end" class="input input-bordered w-full" />
                </label>
                <div class="modal-action">
                    <button type="button" class="btn btn-error" onclick="document.getElementById('event-modal').close()">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>
