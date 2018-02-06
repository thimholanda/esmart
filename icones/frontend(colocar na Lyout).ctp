<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->element('head') ?>
    </head>
    <body>
        <?php if(!($this->request->params["controller"] == 'Usuarios' && $this->request->params['action'] == 'gerlogin')){ ?>
            
        <!-- Chamada para menu em todas as páginas-->    
        <?= $this->element('gertelpri') ?>
        
        <!-- Page Content -->
        <div id="content">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>        
        <?php } else {?>
            <?= $this->Flash->render() ?>
                <div class="tela-login">
                    <div class="logo_inner">
                        <?php echo $this->Html->image('logo-esmart.png', array('width' => '200px', 'height' => '71px')); ?>
                    </div>
                    <?= $this->fetch('content') ?>
                </div> 
            <?php }?>
        
        <?= $this->element('footer') ?>
    </body>
</html>