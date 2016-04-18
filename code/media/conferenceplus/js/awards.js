// Only define the ConferencePlus namespace if not defined.
ConferencePlus = window.ConferencePlus || {};

ConferencePlus = {

    nextNomination   : 0,
    area             : {},
    clonedArea       : {},

    addNomination : function() {
        var html = this.relaceId();
        this.increasenextNominationNumber();

        html = '<div id="nomination' + this.nextNomination + '">' + html + '</div>'
        jQuery('.nominationlist').append(html);

        jQuery('input[name="nominationcount"]').val(this.nextNomination);

    },

    relaceId : function(){
        var html = this.clonedArea.html();
        var newhtml = html
            .replace(/nominee/gm, 'nominee_' + this.nextNomination)
            .replace(/awardcategory_id/gm, 'awardcategory_id_' + this.nextNomination);

        return newhtml;
    },

    setnextNominationNumber : function() {
        this.nextNomination = parseInt(jQuery('input[name="nominationcount"]').val());
    },

    increasenextNominationNumber : function() {
        this.nextNomination++;
    },

    setArea : function(param) {
        this.setnextNominationNumber();
        var tag = '#nomination0';
        this.area = jQuery(tag);
        this.prepareClone();
    },

    prepareClone : function() {
        this.clonedArea = this.area.clone();
    }
};

jQuery(document).ready(function() {
    ConferencePlus.setArea();
});
