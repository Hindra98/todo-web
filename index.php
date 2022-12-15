
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="Vadiny Fotsing" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Todo Web</title>
	<link rel="stylesheet" href="bootstrap.min.css" />
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<div class="container mt-3">

        <nav>
          <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <button class="nav-link active" id="addTask-tab" data-bs-toggle="tab" data-bs-target="#addTask" type="button" role="tab" aria-controls="addTask" aria-selected="true">Ajouter Tâche</button>
            <button class="nav-link" id="travail-tab" data-bs-toggle="tab" data-bs-target="#travail" type="button" role="tab" aria-controls="travail" aria-selected="false" onclick="affiche('travail')">Travail</button>
            <button class="nav-link" id="loisirs-tab" data-bs-toggle="tab" data-bs-target="#loisirs" type="button" role="tab" aria-controls="loisirs" aria-selected="false" onclick="affiche('loisirs')">Loisirs</button>
            <button class="nav-link" id="autres-tab" data-bs-toggle="tab" data-bs-target="#autres" type="button" role="tab" aria-controls="autres" aria-selected="false" onclick="affiche('autres')">Autres</button>
            <button class="nav-link" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button" role="tab" aria-controls="search" aria-selected="false">Rechercher</button>
          </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="addTask" role="tabpanel" aria-labelledby="addTask-tab">
            <p>
            	<form method="post" class="addTask">
            		<h2 class="h2 mb-5">Ajout d'une tâche</h2>
            		<div class="input-group my-3">
			          <span class="input-group-text">Nom de la tâche</span>
			          <input type="text" class="form-control" id="tache" name="tache" placeholder="Entrer le nom de votre tâche" required />
			        </div>
            		<select class="form-select" id="cat" name="categorie" required>
		              <option selected disabled value="">Sélectionnez une catégorie</option>
		              <option value="travail">Travail</option>
		              <option value="loisirs">Loisirs</option>
		              <option value="autres">Autres</option>
		            </select>
		            <button class="btn btn-primary w-100 my-4" type="button" onclick="ajout()">Ajouter</button>
		            <div id="res"></div>
            	</form>
            </p>
          </div>

          <div class="tab-pane fade" id="travail" role="tabpanel" aria-labelledby="travail-tab">
          </div>

          <div class="tab-pane fade" id="loisirs" role="tabpanel" aria-labelledby="loisirs-tab">
          </div>

          <div class="tab-pane fade" id="autres" role="tabpanel" aria-labelledby="autres-tab">
          </div>

          <div class="tab-pane fade" id="search" role="tabpanel" aria-labelledby="search-tab">
            <form class="recherche w-75 mx-auto my-3 form-inline">
              <input type="search" name="search" class="search form-control w-50 p-2 me-3" />
              <button class="btn btn-outline-success searchBtn w-25 p-2" type="button"> Rechercher </button>
            </form>
            <div id="search_response" class="w-100 mx-auto my-2 p-2"></div>
          </div>

        </div>
    </div>

    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
    	function ajout() {
    		var tache = $('#tache').val();
    		var categorie = $('#cat').val();

    		$.ajax({
    			type: 'post',
    			url: 'function.php',
    			data: {
    				tache: tache,
    				categorie: categorie,
    				form: 'ajout'
    			},
    			success: function (response) {
    				$('#tache').val("");
    				$('#cat').val("");
    				$('#res').html(response);
    			}
    		});
    		return false;
    	}
    	

    	function affiche(cat) {

    		$.ajax({
    			type: 'post',
    			url: 'function.php',
    			data: {
    				categorie: cat,
    				form: 'affiche'
    			},
    			success: function (response) {
    				$('#'+cat).html(response);
    			}
    		});
    		return false;
    	}

      $(document).on("click", "button.fin", function() { // Declare avoir termine une tache
        var uid = $(this).attr("uid");
        var page = $(this).attr("page");
        let yet = confirm("Tâche terminée??");

        if (yet)
          $.ajax({
            type: 'post',
            url: 'function.php',
            data: {
              uid: uid,
              form: 'termine'
            },
            success: function (response) {
              // On reactualise la page
              affiche(page);
            }
          });
      });

      $(document).on("click", "button.supp", function() { // Supprime une tache
        var uid = $(this).attr("uid");
        var page = $(this).attr("page");
        let yet = confirm("Voulez vous vraiment supprimer cette tâche?");

        if (yet)
        $.ajax({
          type: 'post',
          url: 'function.php',
          data: {
            uid: uid,
            form: 'supp'
          },
          success: function (response) {
            // On reactualise la page
            affiche(page)
          }
        });
      });

      $('#search-tab').on('click', function() {
        $('#search_response').hide();
      });

      $('.searchBtn').on('click', function() {
        /* Act on the event */
        var search = $('.search').val();
        if ($('.search').val() != "") {
          $('#search_response').show();
          $.ajax({
            type: 'post',
            url: 'function.php',
            data: {
              search: search,
              form: 'search'
            },
            success: function (response) {
              $('#search_response').html(response);
            }
          });
        }
      else
        alert("Vous ne pouvez pas rechercher une tâche vide");
      });
    </script>
	
</body>
</html>