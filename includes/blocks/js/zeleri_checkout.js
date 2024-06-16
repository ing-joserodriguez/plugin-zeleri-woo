import { registerPaymentMethod } from "@woocommerce/blocks-registry";
import { decodeEntities } from "@wordpress/html-entities";
import { getSetting } from "@woocommerce/settings";
import { noticeHandler } from "./notice_handler";

const settings = getSetting("zeleri_woo_oficial_payment_gateways_data", {});
console.log(settings);
const label = decodeEntities(settings.title);

noticeHandler(settings.id);

const Content = () => {
  return decodeEntities(settings.description);
};

const Label = () => {
  const title = decodeEntities(settings.title);
  const imagePath = settings.icon;
  const paymentImage = <img src={imagePath} alt="zeleri logo" />;
  return (
    <div>
      {title}
      {paymentImage}
    </div>
  );
};

const ZeleriWebpayBlocks = {
  name: settings.id,
  label: <Label />,
  content: <Content />,
  edit: <Content />,
  canMakePayment: () => true,
  ariaLabel: label,
  supports: {
    features: settings.supports,
  },
};

registerPaymentMethod(ZeleriWebpayBlocks);
