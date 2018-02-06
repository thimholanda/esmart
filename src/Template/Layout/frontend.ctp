<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->element('head') ?>
    </head>
    <body>
        <div class="container">
            <?php if(!($this->request->params["controller"] == 'Usuarios' && ($this->request->params['action'] == 'gerlogin' || $this->request->params['action'] == 'gersenmod'))){ ?>

            <!-- Chamada para menu em todas as páginas-->    
            <?= $this->element('gertelpri') ?>

                <!-- Page Content -->
                <div id="atl2" class="col-xs-10 col-sm-12">
                    <div id="content">
                        <?= $this->Flash->render() ?>
                        <?= $this->fetch('content') ?>
                    </div>        
                </div>
            </div>
            <?php } else {?>
                <?= $this->Flash->render() ?>
                    <div class="tela-login  <?php if($this->request->params['action'] == 'gersenmod') echo 'tela_alterar_senha' ?>">
                       <div class="logo_inner">
                            <?php echo $this->Html->image('logo-esmart.png', array('width' => '200px', 'height' => '71px')); ?>
                        </div>
                        <?= $this->fetch('content') ?>
                    </div> 
                <?php }?>

            <?= $this->element('footer') ?>
        </div>
    </body>
</html>