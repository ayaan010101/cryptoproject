import React from "react";
import {
  Bitcoin,
  Wallet,
  Shield,
  TrendingUp,
  Clock,
  Users,
} from "lucide-react";

interface FeatureProps {
  icon: React.ReactNode;
  title: string;
  description: string;
}

const Feature = ({ icon, title, description }: FeatureProps) => {
  return (
    <div className="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
      <div className="p-3 bg-primary/10 rounded-full mb-4">{icon}</div>
      <h3 className="text-lg font-semibold mb-2 text-center">{title}</h3>
      <p className="text-gray-500 text-center text-sm">{description}</p>
    </div>
  );
};

interface FeatureSectionProps {
  features?: FeatureProps[];
  title?: string;
  subtitle?: string;
}

const FeatureSection = ({
  features = [
    {
      icon: <Bitcoin className="h-6 w-6 text-primary" />,
      title: "Multi-Currency Support",
      description:
        "Trade Bitcoin, Ethereum, and USDT with seamless wallet integration and real-time balance updates.",
    },
    {
      icon: <Shield className="h-6 w-6 text-primary" />,
      title: "Secure Authentication",
      description:
        "Advanced security with unique recovery keys and multi-factor authentication to protect your assets.",
    },
    {
      icon: <TrendingUp className="h-6 w-6 text-primary" />,
      title: "Real-Time Trading",
      description:
        "Live market data with 30-second updates and advanced charting tools for informed trading decisions.",
    },
    {
      icon: <Wallet className="h-6 w-6 text-primary" />,
      title: "Integrated Wallets",
      description:
        "Manage all your cryptocurrency assets in one place with easy deposit and withdrawal functionality.",
    },
    {
      icon: <Clock className="h-6 w-6 text-primary" />,
      title: "24/7 Market Access",
      description:
        "Trade anytime with continuous market access and instant transaction processing.",
    },
    {
      icon: <Users className="h-6 w-6 text-primary" />,
      title: "User-Friendly Interface",
      description:
        "Intuitive platform design suitable for both beginners and experienced traders.",
    },
  ],
  title = "Platform Features",
  subtitle = "Everything you need to trade cryptocurrencies securely and efficiently",
}: FeatureSectionProps) => {
  return (
    <section className="py-16 px-4 bg-gray-50">
      <div className="max-w-7xl mx-auto">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold mb-4">{title}</h2>
          <p className="text-gray-600 max-w-2xl mx-auto">{subtitle}</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => (
            <Feature
              key={index}
              icon={feature.icon}
              title={feature.title}
              description={feature.description}
            />
          ))}
        </div>
      </div>
    </section>
  );
};

export default FeatureSection;
