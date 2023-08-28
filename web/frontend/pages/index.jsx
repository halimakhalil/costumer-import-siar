import {
  Card,
  Page,
  Layout,
} from "@shopify/polaris";
import { TitleBar } from "@shopify/app-bridge-react";

import { FileImportCard } from "../components";

export default function HomePage() {
  return (
    <Page narrowWidth>
      <TitleBar title="Customer Import" primaryAction={null} />
      <Layout>
        <Layout.Section>
          <FileImportCard />
        </Layout.Section>
      </Layout>
    </Page>
  );
}
